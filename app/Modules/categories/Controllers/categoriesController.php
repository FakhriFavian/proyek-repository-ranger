<?php
namespace App\Modules\categories\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\categories\Models\categories;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class categoriesController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Categories";

	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = categories::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('categories::categories', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'nama_kategori' => ['label' => 'Nama Kategori', 'type' => 'text', 'value' => old("nama_kategori"), 'required' => true],
			'is_active' => ['label' => 'Is Active', 'type' => 'select', 'value' => old("is_active"), 'required' => true, 'options' => ['1' => 'Ya', '0' => 'Tidak']],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('categories::categories_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'nama_kategori' => 'required',
			'is_active' => 'required',
			
		]);

		$categories = new categories();
		$categories->nama_kategori = $request->input("nama_kategori");
		$categories->is_active = $request->input("is_active");
		
		$categories->created_by = Auth::id();
		$categories->save();

		$text = 'membuat '.$this->title; //' baru '.$categories->what;
		$this->log($request, $text, ['categories.id' => $categories->id]);
		return redirect()->route('categories.index')->with('message_success', 'Categories berhasil ditambahkan!');
	}

	public function show(Request $request, categories $categories)
	{
		$data['categories'] = $categories;

		$text = 'melihat detail '.$this->title;//.' '.$categories->what;
		$this->log($request, $text, ['categories.id' => $categories->id]);
		return view('categories::categories_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, categories $categories)
	{
		$data['categories'] = $categories;

		
		$data['forms'] = array(
			'nama_kategori' => ['label' => 'Nama Kategori', 'type' => 'text', 'value' => $categories->nama_kategori, 'required' => true, 'id' => 'nama_kategori'],
			'is_active' => ['label' => 'Is Active', 'type' => 'select', 'value' => $categories->is_active, 'required' => true, 'options' => ['1' => 'Ya', '0' => 'Tidak'], 'id' => 'is_active'],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$categories->what;
		$this->log($request, $text, ['categories.id' => $categories->id]);
		return view('categories::categories_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama_kategori' => 'required',
			'is_active' => 'required',
			
		]);

		$categories = categories::find($id);
		$categories->nama_kategori = $request->input("nama_kategori");
		$categories->is_active = $request->input("is_active");
		
		$categories->updated_by = Auth::id();
		$categories->save();


		$text = 'mengedit '.$this->title;//.' '.$categories->what;
		$this->log($request, $text, ['categories.id' => $categories->id]);
		return redirect()->route('categories.index')->with('message_success', 'Categories berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$categories = categories::find($id);
		$categories->deleted_by = Auth::id();
		$categories->save();
		$categories->delete();

		$text = 'menghapus '.$this->title;//.' '.$categories->what;
		$this->log($request, $text, ['categories.id' => $categories->id]);
		return back()->with('message_success', 'Categories berhasil dihapus!');
	}

}
