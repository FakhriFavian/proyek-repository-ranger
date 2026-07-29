<?php
namespace App\Modules\JenisFile\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\JenisFile\Models\JenisFile;

class JenisFileController extends Controller
{
	protected $title = "Jenis File";
	public function index(Request $request)
	{
		$query = JenisFile::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();
		return view('JenisFile::jenisfile', array_merge($data, ['title' => $this->title]));
	}

	public function create()
	{

		$data['forms'] = array(
			'nama' => ['label' => 'Nama', 'type' => 'text', 'value' => old('nama')],
			'slug' => ['label' => 'Slug', 'type' => 'text', 'value' => old('slug')],
			'group' => ['label' => 'Group', 'type' => 'text', 'value' => old('group')],

		);
		return view('JenisFile::jenisfile_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'nama' => 'required',
			'slug' => 'required',
			'group' => 'required',

		]);

		$jenisfile = new JenisFile();
		$jenisfile->nama = $request->input("nama");
		$jenisfile->slug = $request->input("slug");
		$jenisfile->group = $request->input("group");

		$jenisfile->created_by = Auth::id();
		$jenisfile->save();

		return redirect()->route('jenisfile.index')->with('message_success', 'Jenis File berhasil ditambahkan!');
	}

	public function edit(JenisFile $jenisfile)
	{
		$data['jenisfile'] = $jenisfile;


		$data['forms'] = array(
			'nama' => ['label' => 'Nama', 'type' => 'text', 'value' => $jenisfile->nama, 'id' => 'nama'],
			'slug' => ['label' => 'Slug', 'type' => 'text', 'value' => $jenisfile->slug, 'id' => 'slug'],
			'group' => ['label' => 'Group', 'type' => 'text', 'value' => $jenisfile->group, 'id' => 'group'],

		);

		return view('JenisFile::jenisfile_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama' => 'required',
			'slug' => 'required',
			'group' => 'required',

		]);

		$jenisfile = JenisFile::find($id);
		$jenisfile->nama = $request->input("nama");
		$jenisfile->slug = $request->input("slug");
		$jenisfile->group = $request->input("group");

		$jenisfile->updated_by = Auth::id();
		$jenisfile->save();

		return redirect()->route('jenisfile.index')->with('message_success', 'Jenis File berhasil diubah!');
	}

	public function destroy($id)
	{
		$jenisfile = JenisFile::find($id);
		$jenisfile->deleted_by = Auth::id();
		$jenisfile->save();
		$jenisfile->delete();

		return back()->with('message_success', 'Jenis File berhasil dihapus!');
	}

}
