<?php
namespace App\Modules\Files\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Files\Models\Files;
use App\Modules\JenisFile\Models\JenisFile;

class FilesController extends Controller
{
	protected $title = "Files";
	public function index(Request $request)
	{
		$query = Files::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();
		return view('Files::files', array_merge($data, ['title' => $this->title]));
	}

	public function create()
	{
		$ref_jenis_file = JenisFile::all()->pluck('nama','id');

		$data['forms'] = array(
			'table_name' => ['label' => 'Table Name', 'type' => 'text', 'value' => old('table_name'), 'required' => true],
			'table_id' => ['label' => 'Table Id', 'type' => 'text', 'value' => old('table_id'), 'required' => true],
			'id_jenis' => ['label' => 'Jenis', 'type' => 'select', 'value' => null, 'options' => $ref_jenis_file->all(), 'class' => 'select2'],
			'nama_file' => ['label' => 'Nama File', 'type' => 'text', 'value' => old('nama_file'), 'required' => true],
			'path_file' => ['label' => 'Path File', 'type' => 'text', 'value' => old('path_file'), 'required' => true],
			'file_size' => ['label' => 'File Size', 'type' => 'text', 'value' => old('file_size'), 'required' => true],
			'file_type' => ['label' => 'File Type', 'type' => 'text', 'value' => old('file_type'), 'required' => true],

		);
		return view('Files::files_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'table_name' => 'required',
			'table_id' => 'required',
			'id_jenis' => 'required',
			'nama_file' => 'required',
			'path_file' => 'required',
			'file_size' => 'required',
			'file_type' => 'required',

		]);

		$files = new Files();
		$files->table_name = $request->input("table_name");
		$files->table_id = $request->input("table_id");
		$files->id_jenis = $request->input("id_jenis");
		$files->nama_file = $request->input("nama_file");
		$files->path_file = $request->input("path_file");
		$files->file_size = $request->input("file_size");
		$files->file_type = $request->input("file_type");

		$files->created_by = Auth::id();
		$files->save();

		return redirect()->route('files.index')->with('message_success', 'Files berhasil ditambahkan!');
	}

	public function show(Files $files)
	{
		$data['files'] = $files;

		return view('Files::files_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Files $files)
	{
		$data['files'] = $files;

		$ref_jenis_file = JenisFile::all()->pluck('nama','id');

		$data['forms'] = array(
			'table_name' => ['label' => 'Table Name', 'type' => 'text', 'value' => $files->table_name, 'required' => true, 'id' => 'table_name'],
			'table_id' => ['label' => 'Table Id', 'type' => 'text', 'value' => $files->table_id, 'required' => true, 'id' => 'table_id'],
			'id_jenis' => ['label' => 'Jenis', 'type' => 'select', 'value' => $files->id_jenis, 'options' => $ref_jenis_file->all(), 'class' => 'select2'],
			'nama_file' => ['label' => 'Nama File', 'type' => 'text', 'value' => $files->nama_file, 'required' => true, 'id' => 'nama_file'],
			'path_file' => ['label' => 'Path File', 'type' => 'text', 'value' => $files->path_file, 'required' => true, 'id' => 'path_file'],
			'file_size' => ['label' => 'File Size', 'type' => 'text', 'value' => $files->file_size, 'required' => true, 'id' => 'file_size'],
			'file_type' => ['label' => 'File Type', 'type' => 'text', 'value' => $files->file_type, 'required' => true, 'id' => 'file_type'],

		);

		return view('Files::files_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'table_name' => 'required',
			'table_id' => 'required',
			'id_jenis' => 'required',
			'nama_file' => 'required',
			'path_file' => 'required',
			'file_size' => 'required',
			'file_type' => 'required',

		]);

		$files = Files::find($id);
		$files->table_name = $request->input("table_name");
		$files->table_id = $request->input("table_id");
		$files->id_jenis = $request->input("id_jenis");
		$files->nama_file = $request->input("nama_file");
		$files->path_file = $request->input("path_file");
		$files->file_size = $request->input("file_size");
		$files->file_type = $request->input("file_type");

		$files->updated_by = Auth::id();
		$files->save();

		return redirect()->route('files.index')->with('message_success', 'Files berhasil diubah!');
	}

	public function destroy($id)
	{
		$files = Files::find($id);
		$files->deleted_by = Auth::id();
		$files->save();
		$files->delete();

		return back()->with('message_success', 'Files berhasil dihapus!');
	}

}
