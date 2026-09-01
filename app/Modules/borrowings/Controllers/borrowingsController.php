<?php
namespace App\Modules\borrowings\Controllers;

use Carbon\Carbon;
use App\Helpers\Logger;
use App\Services\BorrowingStockService;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Users\Models\Users;
use App\Modules\borrowings\Models\borrowings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class borrowingsController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Borrowings";
	protected BorrowingStockService $stockService;

	public function __construct(Log $log, BorrowingStockService $stockService)
	{
		$this->log = $log;
		$this->stockService = $stockService;
	}

	public function index(Request $request)
	{
		$query = borrowings::with('user');
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('borrowings::borrowings', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_users = Users::orderBy('name')->pluck('name', 'id');

		$data['forms'] = array(
			'user_id' => ['label' => 'Peminjam', 'type' => 'select', 'value' => old('user_id'), 'options' => $ref_users->all(), 'required' => true, 'class' => 'select2'],
			'jam_mulai' => ['label' => 'Jam Mulai', 'type' => 'datetime-local', 'value' => old('jam_mulai'), 'required' => true],
			'jam_selesai' => ['label' => 'Jam Selesai', 'type' => 'datetime-local', 'value' => old('jam_selesai'), 'required' => true],
			'status' => ['label' => 'Status', 'type' => 'select', 'value' => 'menunggu', 'options' => ['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui'], 'required' => true],
			'catatan_admin' => ['label' => 'Catatan', 'type' => 'textarea', 'value' => old('catatan_admin'), 'required' => false],
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('borrowings::borrowings_create', array_merge($data, ['title' => $this->title]));
	}

	/**
	 * Simpan borrowing baru yang dibuat melalui admin panel.
	 *
	 * CATATAN STOK:
	 * - Stok TIDAK dikurangi di sini karena borrowing baru masih dalam status 'menunggu' atau 'disetujui'.
	 * - Stok akan otomatis dikurangi saat status BERUBAH ke 'dipinjam' melalui update() + BorrowingStockService.
	 * - Sebelum mengubah ke 'dipinjam', borrowing HARUS memiliki minimal satu item (borrowing_details).
	 *
	 * Flow yang aman:
	 * 1. Admin membuat borrowing (status: menunggu/disetujui) → STOK TIDAK BERKURANG
	 * 2. Admin tambahkan items via borrowing_details → STOK MASIH TIDAK BERKURANG
	 * 3. Admin ubah status ke 'dipinjam' → STOK BERKURANG (via BorrowingStockService::adjustBorrowingStock)
	 * 4. Saat dikembalikan (status 'dikembalikan') → STOK KEMBALI BERTAMBAH
	 */
	function store(Request $request)
	{
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
			'jam_mulai' => 'required|date_format:Y-m-d\\TH:i',
			'jam_selesai' => 'required|date_format:Y-m-d\\TH:i|after:jam_mulai',
			'status' => 'required|in:menunggu,disetujui',
			'catatan_admin' => 'nullable|string',
		]);

		$borrowings = new borrowings();
		$borrowings->user_id = $request->input('user_id');
		$borrowings->jam_mulai = Carbon::createFromFormat('Y-m-d\\TH:i', $request->input('jam_mulai'))->format('Y-m-d H:i:s');
		$borrowings->jam_selesai = Carbon::createFromFormat('Y-m-d\\TH:i', $request->input('jam_selesai'))->format('Y-m-d H:i:s');
		$borrowings->status = $request->input('status');
		$borrowings->catatan_admin = $request->input('catatan_admin');
		$borrowings->created_by = Auth::id();
		$borrowings->save();

		$text = 'membuat '.$this->title; //' baru '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return redirect()->route('borrowings.index')->with('message_success', 'Borrowings berhasil ditambahkan!');
	}

	public function show(Request $request, borrowings $borrowings)
	{
		$data['borrowings'] = $borrowings;

		$text = 'melihat detail '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return view('borrowings::borrowings_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, borrowings $borrowings)
	{
		$borrowings->load('details.item');
		$data['borrowings'] = $borrowings;
		$ref_users = Users::orderBy('name')->pluck('name', 'id');

		$data['forms'] = array(
			'user_id' => ['label' => 'Peminjam', 'type' => 'select', 'value' => $borrowings->user_id, 'options' => $ref_users->all(), 'required' => true, 'class' => 'select2', 'id' => 'user_id'],
			'jam_mulai' => ['label' => 'Jam Mulai', 'type' => 'datetime-local', 'value' => Carbon::parse($borrowings->jam_mulai)->format('Y-m-d\\TH:i'), 'required' => true, 'id' => 'jam_mulai'],
			'jam_selesai' => ['label' => 'Jam Selesai', 'type' => 'datetime-local', 'value' => Carbon::parse($borrowings->jam_selesai)->format('Y-m-d\\TH:i'), 'required' => true, 'id' => 'jam_selesai'],
			'status' => ['label' => 'Status', 'type' => 'select', 'value' => $borrowings->status, 'options' => BorrowingStockService::STATUSES, 'required' => true, 'id' => 'status'],
			'catatan_admin' => ['label' => 'Catatan', 'type' => 'textarea', 'value' => $borrowings->catatan_admin, 'required' => false, 'id' => 'catatan_admin'],
		);

		$text = 'membuka form edit '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return view('borrowings::borrowings_update', array_merge($data, ['title' => $this->title]));
	}

	/**
	 * Update borrowing dan handle perubahan stok berdasarkan transisi status.
	 *
	 * MANAJEMEN STOK OTOMATIS:
	 * - Transisi 'menunggu/disetujui' → 'dipinjam': STOK BERKURANG (decrement sesuai jumlah di borrowing_details)
	 * - Transisi 'dipinjam' → 'dikembalikan': STOK BERTAMBAH (increment sesuai jumlah di borrowing_details)
	 * - Status lainnya: STOK TIDAK BERUBAH
	 *
	 * Semua operasi dilakukan dalam DB::transaction() untuk keamanan (mencegah race condition).
	 * Validasi otomatis: borrowing harus punya minimal 1 item sebelum bisa status 'dipinjam'.
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
			'jam_mulai' => 'required|date_format:Y-m-d\\TH:i',
			'jam_selesai' => 'required|date_format:Y-m-d\\TH:i|after:jam_mulai',
			'status' => 'required|in:menunggu,disetujui,dipinjam,dikembalikan,ditolak',
			'catatan_admin' => 'nullable|string',
		]);

		$borrowings = borrowings::findOrFail($id);
		$this->stockService->updateBorrowing($borrowings, [
			'user_id' => $request->input('user_id'),
			'jam_mulai' => Carbon::createFromFormat('Y-m-d\\TH:i', $request->input('jam_mulai'))->format('Y-m-d H:i:s'),
			'jam_selesai' => Carbon::createFromFormat('Y-m-d\\TH:i', $request->input('jam_selesai'))->format('Y-m-d H:i:s'),
			'status' => $request->input('status'),
			'catatan_admin' => $request->input('catatan_admin'),
			'updated_by' => Auth::id(),
		]);


		$text = 'mengedit '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return redirect()->route('borrowings.index')->with('message_success', 'Borrowings berhasil diubah!');
	}

	/**
	 * Hapus borrowing dengan validasi keamanan stok.
	 *
	 * ATURAN PENGHAPUSAN:
	 * - HANYA borrowing yang BUKAN status 'dipinjam' yang bisa dihapus.
	 * - Borrowing dengan status 'dipinjam' TIDAK boleh dihapus (karena stok sudah dikurangi).
	 * - Stok otomatis dikembalikan jika diperlukan melalui BorrowingStockService.
	 */
	public function destroy(Request $request, $id)
	{
		$borrowings = borrowings::findOrFail($id);
		$borrowings->deleted_by = Auth::id();
		$this->stockService->deleteBorrowing($borrowings);

		$text = 'menghapus '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return back()->with('message_success', 'Borrowings berhasil dihapus!');
	}

}
