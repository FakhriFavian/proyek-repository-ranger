<?php
namespace App\Modules\Pengguna\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Pengguna\Models\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Pengguna";

	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Pengguna::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Pengguna::pengguna', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'email' => ['label' => 'Email', 'type' => 'text', 'value' => old("email"), 'required' => false],
			'nama' => ['label' => 'Nama', 'type' => 'text', 'value' => old("nama"), 'required' => true],
			'nisn' => ['label' => 'Nisn', 'type' => 'text', 'value' => old("nisn"), 'required' => false],
			'password' => ['label' => 'Password', 'type' => 'text', 'value' => old("password"), 'required' => true],
			'role' => ['label' => 'Role', 'type' => 'text', 'value' => old("role"), 'required' => true],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Pengguna::pengguna_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'email' => 'required',
			'nama' => 'required',
			'nisn' => 'required',
			'password' => 'required',
			'role' => 'required',
			
		]);

		$pengguna = new Pengguna();
		$pengguna->email = $request->input("email");
		$pengguna->nama = $request->input("nama");
		$pengguna->nisn = $request->input("nisn");
		$pengguna->password = $request->input("password");
		$pengguna->role = $request->input("role");
		
		$pengguna->created_by = Auth::id();
		$pengguna->save();

		$text = 'membuat '.$this->title; //' baru '.$pengguna->what;
		$this->log($request, $text, ['pengguna.id' => $pengguna->id]);
		return redirect()->route('pengguna.index')->with('message_success', 'Pengguna berhasil ditambahkan!');
	}

	public function show(Request $request, Pengguna $pengguna)
	{
		$data['pengguna'] = $pengguna;

		$text = 'melihat detail '.$this->title;//.' '.$pengguna->what;
		$this->log($request, $text, ['pengguna.id' => $pengguna->id]);
		return view('Pengguna::pengguna_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Pengguna $pengguna)
	{
		$data['pengguna'] = $pengguna;

		
		$data['forms'] = array(
			'email' => ['label' => 'Email', 'type' => 'text', 'value' => $pengguna->email, 'required' => false, 'id' => 'email'],
			'nama' => ['label' => 'Nama', 'type' => 'text', 'value' => $pengguna->nama, 'required' => true, 'id' => 'nama'],
			'nisn' => ['label' => 'Nisn', 'type' => 'text', 'value' => $pengguna->nisn, 'required' => false, 'id' => 'nisn'],
			'password' => ['label' => 'Password', 'type' => 'text', 'value' => $pengguna->password, 'required' => true, 'id' => 'password'],
			'role' => ['label' => 'Role', 'type' => 'text', 'value' => $pengguna->role, 'required' => true, 'id' => 'role'],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$pengguna->what;
		$this->log($request, $text, ['pengguna.id' => $pengguna->id]);
		return view('Pengguna::pengguna_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'email' => 'required',
			'nama' => 'required',
			'nisn' => 'required',
			'password' => 'required',
			'role' => 'required',
			
		]);

		$pengguna = Pengguna::find($id);
		$pengguna->email = $request->input("email");
		$pengguna->nama = $request->input("nama");
		$pengguna->nisn = $request->input("nisn");
		$pengguna->password = $request->input("password");
		$pengguna->role = $request->input("role");
		
		$pengguna->updated_by = Auth::id();
		$pengguna->save();


		$text = 'mengedit '.$this->title;//.' '.$pengguna->what;
		$this->log($request, $text, ['pengguna.id' => $pengguna->id]);
		return redirect()->route('pengguna.index')->with('message_success', 'Pengguna berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$pengguna = Pengguna::find($id);
		$pengguna->deleted_by = Auth::id();
		$pengguna->save();
		$pengguna->delete();

		$text = 'menghapus '.$this->title;//.' '.$pengguna->what;
		$this->log($request, $text, ['pengguna.id' => $pengguna->id]);
		return back()->with('message_success', 'Pengguna berhasil dihapus!');
	}

}
