<?php

namespace App\Services;

use App\Modules\Items\Models\Items;
use App\Modules\borrowings\Models\borrowings;
use App\Modules\borrowing_details\Models\borrowing_details;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingStockService
{
    public const STATUSES = [
        'menunggu' => 'Menunggu',
        'dipinjam' => 'Dipinjam',
        'dikembalikan' => 'Dikembalikan',
        'ditolak' => 'Ditolak',
    ];

    public function updateBorrowing(borrowings $borrowing, array $attributes): borrowings
    {
        return DB::transaction(function () use ($borrowing, $attributes) {
            $lockedBorrowing = borrowings::whereKey($borrowing->id)->lockForUpdate()->firstOrFail();
            $oldStatus = $lockedBorrowing->status;
            $newStatus = $attributes['status'];

            $this->validateTransition($oldStatus, $newStatus);

            if ($oldStatus !== 'dipinjam' && $newStatus === 'dipinjam' && !$lockedBorrowing->details()->exists()) {
                throw ValidationException::withMessages([
                    'status' => 'Peminjaman harus memiliki minimal satu item sebelum berstatus dipinjam.',
                ]);
            }

            if ($oldStatus !== $newStatus) {
                if ($oldStatus === 'menunggu' && $newStatus === 'dipinjam') {
                    $this->adjustBorrowingStock($lockedBorrowing, -1);
                } elseif ($oldStatus === 'dipinjam' && $newStatus === 'dikembalikan') {
                    $this->adjustBorrowingStock($lockedBorrowing, 1);
                }
            }

            foreach ($attributes as $attribute => $value) {
                $lockedBorrowing->setAttribute($attribute, $value);
            }
            $lockedBorrowing->save();

            return $lockedBorrowing;
        });
    }

    public function updateDetail(borrowing_details $detail, array $attributes): borrowing_details
    {
        return DB::transaction(function () use ($detail, $attributes) {
            $lockedDetail = borrowing_details::whereKey($detail->id)->lockForUpdate()->firstOrFail();
            $oldBorrowingId = $lockedDetail->borrowing_id;
            $newBorrowingId = $attributes['borrowing_id'];
            $borrowingIds = collect([$oldBorrowingId, $newBorrowingId])->unique()->sort()->values();
            $borrowings = borrowings::whereIn('id', $borrowingIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $oldBorrowing = $borrowings->get($oldBorrowingId);
            $newBorrowing = $borrowings->get($newBorrowingId);

            if (!$oldBorrowing || !$newBorrowing) {
                throw ValidationException::withMessages(['borrowing_id' => 'Borrowing tidak ditemukan.']);
            }

            if ($oldBorrowing->status === 'dikembalikan' || $newBorrowing->status === 'dikembalikan') {
                throw ValidationException::withMessages(['borrowing_id' => 'Detail tidak dapat diubah setelah peminjaman dikembalikan.']);
            }

            $stockChanges = [];
            if ($oldBorrowing->status === 'dipinjam') {
                $stockChanges[$lockedDetail->item_id] = ($stockChanges[$lockedDetail->item_id] ?? 0) + $lockedDetail->jumlah;
            }
            if ($newBorrowing->status === 'dipinjam') {
                $stockChanges[$attributes['item_id']] = ($stockChanges[$attributes['item_id']] ?? 0) - $attributes['jumlah'];
            }

            $this->applyStockChanges($stockChanges);
            foreach ($attributes as $attribute => $value) {
                $lockedDetail->setAttribute($attribute, $value);
            }
            $lockedDetail->save();

            return $lockedDetail;
        });
    }

    public function deleteDetail(borrowing_details $detail): void
    {
        DB::transaction(function () use ($detail) {
            $lockedDetail = borrowing_details::whereKey($detail->id)->lockForUpdate()->firstOrFail();
            $borrowing = borrowings::whereKey($lockedDetail->borrowing_id)->lockForUpdate()->firstOrFail();

            if ($borrowing->status === 'dikembalikan') {
                throw ValidationException::withMessages(['borrowing_id' => 'Detail tidak dapat dihapus setelah peminjaman dikembalikan.']);
            }

            if ($borrowing->status === 'dipinjam') {
                $this->applyStockChanges([$lockedDetail->item_id => $lockedDetail->jumlah]);
            }

            $lockedDetail->deleted_by = $detail->deleted_by;
            $lockedDetail->delete();
        });
    }


    public function deleteBorrowing(borrowings $borrowing): void
    {
        DB::transaction(function () use ($borrowing) {
            $lockedBorrowing = borrowings::whereKey($borrowing->id)->lockForUpdate()->firstOrFail();

            if ($lockedBorrowing->status === 'dipinjam') {
                throw ValidationException::withMessages(['status' => 'Peminjaman berstatus dipinjam tidak dapat dihapus.']);
            }

            $lockedBorrowing->deleted_by = $borrowing->deleted_by;
            $lockedBorrowing->delete();
        });
    }

    private function validateTransition(string $oldStatus, string $newStatus): void
    {
        if (!array_key_exists($newStatus, self::STATUSES)) {
            throw ValidationException::withMessages(['status' => 'Status peminjaman tidak valid.']);
        }

        $allowed = [
            'menunggu' => ['menunggu', 'dipinjam', 'ditolak'],
            'dipinjam' => ['dipinjam', 'dikembalikan'],
            'dikembalikan' => ['dikembalikan'],
            'ditolak' => ['ditolak'],
        ];

        if (!in_array($newStatus, $allowed[$oldStatus] ?? [], true)) {
            throw ValidationException::withMessages(['status' => 'Transisi status peminjaman tidak diizinkan.']);
        }
    }

    private function adjustBorrowingStock(borrowings $borrowing, int $direction): void
    {
        $stockChanges = [];
        foreach ($borrowing->details()->get() as $detail) {
            $stockChanges[$detail->item_id] = ($stockChanges[$detail->item_id] ?? 0) + ($direction * $detail->jumlah);
        }

        $this->applyStockChanges($stockChanges);
    }

    private function applyStockChanges(array $stockChanges): void
    {
        $stockChanges = array_filter($stockChanges, static fn ($change) => $change !== 0);
        if ($stockChanges === []) {
            return;
        }

        $items = Items::whereIn('id', array_keys($stockChanges))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($stockChanges as $itemId => $change) {
            $item = $items->get($itemId);
            if (!$item) {
                throw ValidationException::withMessages(['item_id' => 'Item tidak ditemukan.']);
            }

            $newAvailable = $item->stok_tersedia + $change;
            if ($newAvailable < 0) {
                throw ValidationException::withMessages(['item_id' => 'Stok tersedia tidak mencukupi.']);
            }
            if ($newAvailable > $item->stok_total) {
                throw ValidationException::withMessages(['item_id' => 'Stok tersedia tidak boleh melebihi stok total.']);
            }

            $item->stok_tersedia = $newAvailable;
            $item->save();
        }
    }
}
