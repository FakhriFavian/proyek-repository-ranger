<?php
namespace App\Modules\borrowing_details\Controllers;

use App\Helpers\Logger;
use App\Services\BorrowingStockService;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Items\Models\Items;
use App\Modules\borrowings\Models\borrowings as Borrowing;
use App\Modules\borrowing_details\Models\borrowing_details;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class borrowing_detailsController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Borrowing Details";
	protected BorrowingStockService $stockService;

	public function __construct(Log $log, BorrowingStockService $stockService)
	{
		$this->log = $log;
		$this->stockService = $stockService;
	}

	public function index(Request $request)
	{
		$query = borrowing_details::with(['borrowing.user', 'item']);
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('borrowing_details::borrowing_details', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_borrowings = Borrowing::with('user')->orderByDesc('created_at')->get()->mapWithKeys(function ($borrowing) {
			$userName = $borrowing->user?->name ?? 'Tanpa nama';
			return [$borrowing->id => $borrowing->id.' - '.$userName.' - '.$borrowing->jam_mulai];
		});
		$ref_items = Items::where('is_active', 1)->orderBy('nama_item')->pluck('nama_item', 'id');
		
		$data['forms'] = array(
			'borrowing_id' => ['label' => 'Peminjaman', 'type' => 'select', 'value' => old("borrowing_id"), 'required' => true, 'options' => $ref_borrowings->all(), 'class' => 'select2'],
			'item_id' => ['label' => 'Barang', 'type' => 'select', 'value' => old("item_id"), 'required' => true, 'options' => $ref_items->all(), 'class' => 'select2'],
			'kondisi_barang' => ['label' => 'Kondisi Barang', 'type' => 'text', 'value' => old("kondisi_barang"), 'required' => true],
			'denda' => ['label' => 'Denda', 'type' => 'number', 'value' => old("denda"), 'required' => true],
			'jumlah' => ['label' => 'Jumlah', 'type' => 'number', 'value' => old("jumlah"), 'required' => true, 'min' => 1],
			'catatan' => ['label' => 'Catatan', 'type' => 'textarea', 'value' => old("catatan"), 'required' => false],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('borrowing_details::borrowing_details_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'borrowing_id' => 'required|exists:borrowings,id',
			'item_id' => 'required|exists:items,id',
			'kondisi_barang' => 'required|string',
			'denda' => 'required|integer|min:0',
			'jumlah' => 'required|integer|min:1',
			'catatan' => 'nullable|string',
			
		]);

		$borrowing = Borrowing::findOrFail($request->input('borrowing_id'));
		if ($borrowing->status !== 'menunggu') {
			return back()->withInput()->with('message_error', 'Detail hanya dapat ditambahkan pada peminjaman yang masih menunggu.');
		}

		$borrowing_details = new borrowing_details();
		$borrowing_details->borrowing_id = $request->input("borrowing_id");
		$borrowing_details->item_id = $request->input("item_id");
		$borrowing_details->kondisi_barang = $request->input("kondisi_barang");
		$borrowing_details->denda = $request->input("denda");
		$borrowing_details->jumlah = $request->input("jumlah");
		$borrowing_details->catatan = $request->input("catatan");
		
		$borrowing_details->created_by = Auth::id();
		$borrowing_details->save();

		$text = 'membuat '.$this->title; //' baru '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return redirect()->route('borrowing_details.index')->with('message_success', 'Borrowing Details berhasil ditambahkan!');
	}

	public function show(Request $request, borrowing_details $borrowing_details)
	{
		$data['borrowing_details'] = $borrowing_details;

		$text = 'melihat detail '.$this->title;//.' '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return view('borrowing_details::borrowing_details_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, borrowing_details $borrowing_details)
	{
		$data['borrowing_details'] = $borrowing_details;

		$ref_borrowings = Borrowing::with('user')->orderByDesc('created_at')->get()->mapWithKeys(function ($borrowing) {
			$userName = $borrowing->user?->name ?? 'Tanpa nama';
			return [$borrowing->id => $borrowing->id.' - '.$userName.' - '.$borrowing->jam_mulai];
		});
		$ref_items = Items::where('is_active', 1)
			->orWhere('id', $borrowing_details->item_id)
			->orderBy('nama_item')
			->pluck('nama_item', 'id');
		
		$data['forms'] = array(
			'borrowing_id' => ['label' => 'Peminjaman', 'type' => 'select', 'value' => $borrowing_details->borrowing_id, 'required' => true, 'options' => $ref_borrowings->all(), 'class' => 'select2', 'id' => 'borrowing_id'],
			'item_id' => ['label' => 'Barang', 'type' => 'select', 'value' => $borrowing_details->item_id, 'required' => true, 'options' => $ref_items->all(), 'class' => 'select2', 'id' => 'item_id'],
			'kondisi_barang' => ['label' => 'Kondisi Barang', 'type' => 'text', 'value' => $borrowing_details->kondisi_barang, 'required' => true, 'id' => 'kondisi_barang'],
			'denda' => ['label' => 'Denda', 'type' => 'number', 'value' => $borrowing_details->denda, 'required' => true, 'id' => 'denda'],
			'jumlah' => ['label' => 'Jumlah', 'type' => 'number', 'value' => $borrowing_details->jumlah, 'required' => true, 'min' => 1, 'id' => 'jumlah'],
			'catatan' => ['label' => 'Catatan', 'type' => 'textarea', 'value' => $borrowing_details->catatan, 'required' => false, 'id' => 'catatan'],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return view('borrowing_details::borrowing_details_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'borrowing_id' => 'required|exists:borrowings,id',
			'item_id' => 'required|exists:items,id',
			'kondisi_barang' => 'required|string',
			'denda' => 'required|integer|min:0',
			'jumlah' => 'required|integer|min:1',
			'catatan' => 'nullable|string',
			
		]);

		$borrowing_details = borrowing_details::findOrFail($id);
		$this->stockService->updateDetail($borrowing_details, [
			'borrowing_id' => $request->input("borrowing_id"),
			'item_id' => $request->input("item_id"),
			'kondisi_barang' => $request->input("kondisi_barang"),
			'denda' => $request->input("denda"),
			'jumlah' => $request->input("jumlah"),
			'catatan' => $request->input("catatan"),
			'updated_by' => Auth::id(),
		]);


		$text = 'mengedit '.$this->title;//.' '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return redirect()->route('borrowing_details.index')->with('message_success', 'Borrowing Details berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$borrowing_details = borrowing_details::findOrFail($id);
		$borrowing_details->deleted_by = Auth::id();
		$this->stockService->deleteDetail($borrowing_details);

		$text = 'menghapus '.$this->title;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return back()->with('message_success', 'Borrowing Details berhasil dihapus!');
	}

}
