<?php
namespace App\Modules\Items\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Items\Models\Items;
use App\Modules\categories\Models\categories;
use App\Modules\borrowing_details\Models\borrowing_details;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
		$ref_categories = categories::where('is_active', 1)->orderBy('nama_kategori')->pluck('nama_kategori', 'id');
		$data['forms'] = array(
			'nama_item' => ['label' => 'Nama Item', 'type' => 'text', 'value' => old('nama_item'), 'required' => true],
			'category_id' => ['label' => 'Kategori', 'type' => 'select', 'value' => old('category_id'), 'options' => $ref_categories->all(), 'required' => true, 'class' => 'select2'],
			'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'value' => old('deskripsi'), 'required' => false],
			'foto' => ['label' => 'Foto', 'type' => 'file', 'required' => false, 'accept' => 'image/jpeg,image/png'],
			'stok_total' => ['label' => 'Stok Total', 'type' => 'number', 'value' => old('stok_total'), 'required' => true, 'min' => 0],
			'stok_tersedia' => ['label' => 'Stok Tersedia', 'type' => 'number', 'value' => old('stok_tersedia'), 'required' => false, 'min' => 0],
			'is_active' => ['label' => 'Is Active', 'type' => 'select', 'value' => old('is_active', 1), 'options' => ['1' => 'Ya', '0' => 'Tidak'], 'required' => true],
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Items::items_create', array_merge($data, ['title' => $this->title]));
	}

	public function store(Request $request)
	{
		if (!$request->filled('stok_tersedia')) {
			$request->merge(['stok_tersedia' => $request->input('stok_total')]);
		}

		$this->validate($request, [
			'nama_item' => 'required|string',
			'category_id' => 'required|exists:categories,id',
			'deskripsi' => 'nullable|string',
			'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
			'stok_total' => 'required|integer|min:0',
			'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
			'is_active' => 'required|in:0,1',
		]);

		$items = new Items();
		$items->nama_item = $request->input('nama_item');
		$items->category_id = $request->input('category_id');
		$items->deskripsi = $request->input('deskripsi');
		$items->foto = $request->hasFile('foto')
			? $request->file('foto')->store('items', 'public')
			: null;
		$items->stok_total = $request->input('stok_total');
		$items->stok_tersedia = $request->input('stok_tersedia');
		$items->is_active = $request->input('is_active');
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
		$ref_categories = categories::where('is_active', 1)->orderBy('nama_kategori')->pluck('nama_kategori', 'id');

		$data['forms'] = array(
			'nama_item' => ['label' => 'Nama Item', 'type' => 'text', 'value' => $items->nama_item, 'required' => true, 'id' => 'nama_item'],
			'category_id' => ['label' => 'Kategori', 'type' => 'select', 'value' => $items->category_id, 'options' => $ref_categories->all(), 'required' => true, 'class' => 'select2', 'id' => 'category_id'],
			'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'value' => $items->deskripsi, 'required' => false, 'id' => 'deskripsi'],
			'foto' => ['label' => 'Foto Baru', 'type' => 'file', 'required' => false, 'accept' => 'image/jpeg,image/png', 'id' => 'foto'],
			'stok_total' => ['label' => 'Stok Total', 'type' => 'number', 'value' => $items->stok_total, 'required' => true, 'min' => 0, 'id' => 'stok_total'],
			'is_active' => ['label' => 'Is Active', 'type' => 'select', 'value' => $items->is_active, 'options' => ['1' => 'Ya', '0' => 'Tidak'], 'required' => true, 'id' => 'is_active'],
		);

		$text = 'membuka form edit '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return view('Items::items_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama_item' => 'required|string',
			'category_id' => 'required|exists:categories,id',
			'deskripsi' => 'nullable|string',
			'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
			'stok_total' => 'required|integer|min:0',
			'is_active' => 'required|in:0,1',
		]);

		$items = Items::find($id);
		$fotoLama = $items->foto;
		$fotoBaru = $request->hasFile('foto')
			? $request->file('foto')->store('items', 'public')
			: null;
		$items->nama_item = $request->input('nama_item');
		$items->category_id = $request->input('category_id');
		$items->deskripsi = $request->input('deskripsi');
		if ($fotoBaru) {
			$items->foto = $fotoBaru;
		}
		$items->stok_total = $request->input('stok_total');
		$items->is_active = $request->input('is_active');
		$items->updated_by = Auth::id();
		$items->save();

		if ($fotoBaru && $fotoLama) {
			Storage::disk('public')->delete($fotoLama);
		}


		$text = 'mengedit '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return redirect()->route('items.index')->with('message_success', 'Items berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$items = Items::findOrFail($id);
		if (borrowing_details::withTrashed()->where('item_id', $items->id)->exists()) {
			return back()->with('message_error', 'Item tidak dapat dihapus karena masih digunakan dalam data peminjaman.');
		}

		$items->deleted_by = Auth::id();
		$items->save();
		$items->delete();

		$text = 'menghapus '.$this->title;//.' '.$items->what;
		$this->log($request, $text, ['items.id' => $items->id]);
		return back()->with('message_success', 'Items berhasil dihapus!');
	}

}
