<?php
namespace App\Modules\borrowing_details\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\borrowing_details\Models\borrowing_details;
use App\Modules\Borrowings\Models\Borrowings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class borrowing_detailsController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Borrowing Details";

	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = borrowing_details::query();
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
		$ref_borrowings = Borrowings::all()->pluck('created_at','id');
		
		$data['forms'] = array(
			'borrowing_id' => ['label' => 'Borrowing Id', 'type' => 'select', 'value' => old("borrowing_id"), 'required' => true, 'options' => $ref_borrowings->all(), 'class' => 'select2'],
			'item_id' => ['label' => 'Item Id', 'type' => 'number', 'value' => old("item_id"), 'required' => true],
			'kondisi_barang' => ['label' => 'Kondisi Barang', 'type' => 'text', 'value' => old("kondisi_barang"), 'required' => true],
			'denda' => ['label' => 'Denda', 'type' => 'number', 'value' => old("denda"), 'required' => true],
			'jumlah' => ['label' => 'Jumlah', 'type' => 'text', 'value' => old("jumlah"), 'required' => true],
			'catatan' => ['label' => 'Catatan', 'type' => 'textarea', 'value' => old("catatan"), 'required' => false],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('borrowing_details::borrowing_details_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'borrowing_id' => 'required',
			'item_id' => 'required',
			'kondisi_barang' => 'required',
			'denda' => 'required',
			'jumlah' => 'required',
			'catatan' => 'required',
			
		]);

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

		$ref_borrowings = Borrowings::all()->pluck('created_at','id');
		
		$data['forms'] = array(
			'borrowing_id' => ['label' => 'Borrowing Id', 'type' => 'select', 'value' => $borrowing_details->borrowing_id, 'required' => true, 'options' => $ref_borrowings->all(), 'class' => 'select2', 'id' => 'borrowing_id'],
			'item_id' => ['label' => 'Item Id', 'type' => 'number', 'value' => $borrowing_details->item_id, 'required' => true, 'id' => 'item_id'],
			'kondisi_barang' => ['label' => 'Kondisi Barang', 'type' => 'text', 'value' => $borrowing_details->kondisi_barang, 'required' => true, 'id' => 'kondisi_barang'],
			'denda' => ['label' => 'Denda', 'type' => 'number', 'value' => $borrowing_details->denda, 'required' => true, 'id' => 'denda'],
			'jumlah' => ['label' => 'Jumlah', 'type' => 'text', 'value' => $borrowing_details->jumlah, 'required' => true, 'id' => 'jumlah'],
			'catatan' => ['label' => 'Catatan', 'type' => 'textarea', 'value' => $borrowing_details->catatan, 'required' => false, 'id' => 'catatan'],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return view('borrowing_details::borrowing_details_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'borrowing_id' => 'required',
			'item_id' => 'required',
			'kondisi_barang' => 'required',
			'denda' => 'required',
			'jumlah' => 'required',
			'catatan' => 'required',
			
		]);

		$borrowing_details = borrowing_details::find($id);
		$borrowing_details->borrowing_id = $request->input("borrowing_id");
		$borrowing_details->item_id = $request->input("item_id");
		$borrowing_details->kondisi_barang = $request->input("kondisi_barang");
		$borrowing_details->denda = $request->input("denda");
		$borrowing_details->jumlah = $request->input("jumlah");
		$borrowing_details->catatan = $request->input("catatan");
		
		$borrowing_details->updated_by = Auth::id();
		$borrowing_details->save();


		$text = 'mengedit '.$this->title;//.' '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return redirect()->route('borrowing_details.index')->with('message_success', 'Borrowing Details berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$borrowing_details = borrowing_details::find($id);
		$borrowing_details->deleted_by = Auth::id();
		$borrowing_details->save();
		$borrowing_details->delete();

		$text = 'menghapus '.$this->title;//.' '.$borrowing_details->what;
		$this->log($request, $text, ['borrowing_details.id' => $borrowing_details->id]);
		return back()->with('message_success', 'Borrowing Details berhasil dihapus!');
	}

}
