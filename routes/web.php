<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Middleware\AuthenticateStudent;
use App\Modules\Items\Models\Items;
use App\Modules\borrowings\Models\borrowings;
use App\Modules\borrowing_details\Models\borrowing_details;
use App\Modules\categories\Models\categories;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

Route::view('/', 'welcome')->name('frontend.index');

Route::get('/user/login', [StudentAuthController::class, 'create'])->name('user.login');
Route::post('/user/login', [StudentAuthController::class, 'store'])->name('user.login.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/role/set/{id_role}', [DashboardController::class, 'changeRole'])->name('dashboard.change.role');
    Route::get('/forcelogout', [DashboardController::class, 'forceLogout'])->name('dashboard.force.logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Route User (Ubah name jadi 'home')
    Route::get('/home', function () {
        $activeCategory = request('category', 'All item');
        $categories = categories::where('is_active', 1)->orderBy('nama_kategori')->get();
        $items = Items::with('category')
            ->where('is_active', 1)
            ->when($activeCategory !== 'All item', function ($query) use ($activeCategory) {
                $query->whereHas('category', function ($categoryQuery) use ($activeCategory) {
                    $categoryQuery->where('nama_kategori', $activeCategory);
                });
            })
            ->orderBy('nama_item')
            ->get();
        return view('user.home', compact('categories', 'items', 'activeCategory'));
    })->name('home');

Route::middleware(AuthenticateStudent::class)->group(function () {

    Route::get('/riwayat', function () {
        $borrowings = borrowings::with(['details.item.category'])
            ->where('user_id', Auth::guard('student')->id())
            ->latest('jam_mulai')
            ->paginate(6)
            ->withQueryString();

        $statuses = [
            'menunggu' => [
                'status' => 'Booking',
                'title' => 'Permintaan peminjaman dibuat',
                'desc' => 'Permintaan peminjaman berhasil dibuat',
                'status_bg' => 'bg-cyan-100 text-cyan-600',
                'icon_bg' => 'bg-cyan-100 text-cyan-500',
                'icon' => 'clipboard',
            ],
            'disetujui' => [
                'status' => 'Disetujui',
                'title' => 'Permintaan disetujui',
                'desc' => 'Permintaan peminjaman telah disetujui oleh admin',
                'status_bg' => 'bg-emerald-100 text-emerald-600',
                'icon_bg' => 'bg-emerald-100 text-emerald-500',
                'icon' => 'check',
            ],
            'dipinjam' => [
                'status' => 'Dipinjam',
                'title' => 'Barang dipinjam',
                'desc' => 'Barang telah diambil oleh peminjam',
                'status_bg' => 'bg-purple-100 text-purple-600',
                'icon_bg' => 'bg-purple-100 text-purple-500',
                'icon' => 'bag',
            ],
            'dikembalikan' => [
                'status' => 'Dikembalikan',
                'title' => 'Barang dikembalikan',
                'desc' => 'Barang telah dikembalikan dalam kondisi baik.',
                'status_bg' => 'bg-slate-200 text-slate-600',
                'icon_bg' => 'bg-slate-200 text-slate-500',
                'icon' => 'download',
            ],
            'ditolak' => [
                'status' => 'Ditolak',
                'title' => 'Permintaan ditolak',
                'desc' => 'Permintaan peminjaman ditolak oleh admin',
                'status_bg' => 'bg-rose-100 text-rose-600',
                'icon_bg' => 'bg-rose-100 text-rose-500',
                'icon' => 'clipboard',
            ],
            'terlambat' => [
                'status' => 'Terlambat',
                'title' => 'Peminjaman terlambat',
                'desc' => 'Peminjaman melewati jadwal pengembalian',
                'status_bg' => 'bg-amber-100 text-amber-600',
                'icon_bg' => 'bg-amber-100 text-amber-500',
                'icon' => 'search',
            ],
        ];

        $timeline = $borrowings->getCollection()->map(function ($borrowing) use ($statuses) {
            $status = $statuses[$borrowing->status] ?? [
                'status' => ucfirst($borrowing->status),
                'title' => 'Status peminjaman',
                'desc' => 'Status peminjaman diperbarui oleh admin',
                'status_bg' => 'bg-slate-200 text-slate-600',
                'icon_bg' => 'bg-slate-200 text-slate-500',
                'icon' => 'clipboard',
            ];
            $detail = $borrowing->details->first();

            return array_merge($status, [
                'date' => Carbon::parse($borrowing->jam_mulai)->translatedFormat('d M Y'),
                'time' => Carbon::parse($borrowing->jam_mulai)->format('H.i').' - '.Carbon::parse($borrowing->jam_selesai)->format('H.i'),
                'item' => $detail?->item?->nama_item ?? 'Barang tidak tersedia',
                'category' => $detail?->item?->category?->nama_kategori ?? 'Tanpa kategori',
                'jumlah' => $detail?->jumlah ?? 0,
                'cat_bg' => 'bg-cyan-100 text-cyan-600',
            ]);
        });

        $stats = [
            'total' => borrowings::where('user_id', Auth::guard('student')->id())->count(),
            'disetujui' => borrowings::where('user_id', Auth::guard('student')->id())->where('status', 'disetujui')->count(),
            'ditolak' => borrowings::where('user_id', Auth::guard('student')->id())->where('status', 'ditolak')->count(),
            'dikembalikan' => borrowings::where('user_id', Auth::guard('student')->id())->where('status', 'dikembalikan')->count(),
        ];

        return view('user.riwayat', compact('timeline', 'borrowings', 'stats'));
    })->name('riwayat');

    Route::get('/peminjaman/konfirmasi', function (Request $request) {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'tanggal' => 'required|string',
            'jam' => ['required', 'regex:/^\d{2}\.\d{2} - \d{2}\.\d{2}$/'],
            'jumlah' => 'required|integer|min:1',
        ]);

        $itemModel = Items::with('category')->where('is_active', 1)->findOrFail($request->query('item_id'));
        if ($itemModel->stok_tersedia < 1) {
            abort(422, 'Barang tidak tersedia.');
        }
        $user = Auth::guard('student')->user();
        $item = [
            'id' => $itemModel->id,
            'name' => $itemModel->nama_item,
            'category' => $itemModel->category?->nama_kategori ?? 'Tanpa kategori',
            'stock' => $itemModel->stok_tersedia,
            'img' => $itemModel->foto ? asset('storage/'.$itemModel->foto) : asset('images/vacuum.jpg'),
        ];

        $tanggal = $request->query('tanggal');
        $jam = $request->query('jam');
        $jumlah = min((int) $request->query('jumlah'), $itemModel->stok_tersedia);

        return view('user.confirm', compact('item', 'tanggal', 'jam', 'jumlah', 'user'));
    })->name('peminjaman.confirm');

    // 2. Route Simpan Peminjaman (Saat tombol MULAI MEMINJAM diklik)
    Route::post('/peminjaman/store', function (Request $request) {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'tanggal' => 'required|string',
            'jam' => ['required', 'regex:/^\d{2}\.\d{2} - \d{2}\.\d{2}$/'],
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            $date = Carbon::createFromLocaleFormat('d F Y', 'id', $request->input('tanggal'));
        } catch (\Throwable) {
            $date = Carbon::parse($request->input('tanggal'));
        }
        $date = $date->startOfDay();
        [$start, $end] = array_map(
            static fn (string $time) => str_replace('.', ':', trim($time)),
            explode('-', $request->input('jam'))
        );
        $startAt = $date->copy()->setTimeFromTimeString($start);
        $endAt = $date->copy()->setTimeFromTimeString($end);

        DB::transaction(function () use ($startAt, $endAt, $request) {
            $item = Items::where('is_active', 1)
                ->whereKey($request->input('item_id'))
                ->lockForUpdate()
                ->firstOrFail();
            $jumlah = (int) $request->input('jumlah');
            if ($jumlah > $item->stok_tersedia) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah peminjaman melebihi stok yang tersedia.',
                ]);
            }

            $borrowing = new borrowings();
            $borrowing->user_id = Auth::guard('student')->id();
            $borrowing->jam_mulai = $startAt->format('Y-m-d H:i:s');
            $borrowing->jam_selesai = $endAt->format('Y-m-d H:i:s');
            $borrowing->status = 'menunggu';
            $borrowing->created_by = Auth::guard('student')->id();
            $borrowing->save();

            $detail = new borrowing_details();
            $detail->borrowing_id = $borrowing->id;
            $detail->item_id = $item->id;
            $detail->kondisi_barang = 'Baik';
            $detail->denda = 0;
            $detail->jumlah = $jumlah;
            $detail->catatan = 0;
            $detail->created_by = Auth::guard('student')->id();
            $detail->save();
        });

        return redirect()->route('riwayat');
    })->name('peminjaman.store');
 
});

require __DIR__ . '/auth.php';
