<?php
namespace App\Modules\Items\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Items\Models\Items;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Items";

	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Items::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Items::items', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$data['forms'] = array(
			'nama_item' => ['label' => 'Nama Item', 'type' => 'text', 'value' => old('nama_item'), 'required' => true],
			'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'value' => old('deskripsi'), 'required' => false],
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Items::items_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'nama_item' => 'required',
			'deskripsi' => 'nullable',
		]);

		$items = new Items();
		$items->nama_item = $request->input('nama_item');
		$items->deskripsi = $request->input('deskripsi');
		$items->created_by = Auth::id();
		$items->save();

		$text = 'membuat '.$this->title; //' baru '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return redirect()->route('items.index')->with('message_success', 'Items berhasil ditambahkan!');
	}

	public function show(Request $request, Items $items)
	{
		$data['items'] = $items;

		$text = 'melihat detail '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return view('Items::items_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Items $items)
	{
		$data['items'] = $items;

		$data['forms'] = array(
			'nama_item' => ['label' => 'Nama Item', 'type' => 'text', 'value' => $items->nama_item, 'required' => true, 'id' => 'nama_item'],
			'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'value' => $items->deskripsi, 'required' => false, 'id' => 'deskripsi'],
		);

		$text = 'membuka form edit '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return view('Items::items_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama_item' => 'required',
			'deskripsi' => 'nullable',
		]);

		$items = Items::find($id);
		$items->nama_item = $request->input('nama_item');
		$items->deskripsi = $request->input('deskripsi');
		$items->updated_by = Auth::id();
		$items->save();


		$text = 'mengedit '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return redirect()->route('items.index')->with('message_success', 'Items berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$items = Items::find($id);
		$items->deleted_by = Auth::id();
		$items->save();
		$items->delete();

		$text = 'menghapus '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return back()->with('message_success', 'Items berhasil dihapus!');
	}

}
