<?php
namespace App\Modules\Config\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Config\Models\Config;

class ConfigController extends Controller
{
	protected $title = "Config";
	public function index(Request $request)
	{
		$query = Config::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();
		return view('Config::config', array_merge($data, ['title' => $this->title]));
	}

	public function create()
	{

		$data['forms'] = array(
			'key' => ['label' => 'Key', 'type' => 'text', 'value' => old('key'), 'required' => true],
			'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'value' => old('deskripsi'), 'required' => true],
			'default' => ['label' => 'Default', 'type' => 'text', 'value' => old('default'), 'required' => true],
			'form_type' => ['label' => 'Form Type', 'type' => 'text', 'value' => old('form_type'), 'required' => true],
			'form_label' => ['label' => 'Form Label', 'type' => 'text', 'value' => old('form_label'), 'required' => true],
			'value' => ['label' => 'Value', 'type' => 'textarea', 'value' => old('value'), 'required' => true],

		);
		return view('Config::config_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'key' => 'required',
			'deskripsi' => 'required',
			'default' => 'required',
			'form_type' => 'required',
			'form_label' => 'required',
			'value' => 'required',

		]);

		$config = new Config();
		$config->key = $request->input("key");
		$config->deskripsi = $request->input("deskripsi");
		$config->default = $request->input("default");
		$config->form_type = $request->input("form_type");
		$config->form_label = $request->input("form_label");
		$config->value = $request->input("value");

		$config->created_by = Auth::id();
		$config->save();

		return redirect()->route('config.index')->with('message_success', 'Config berhasil ditambahkan!');
	}

	public function show(Config $config)
	{
		$data['config'] = $config;

		return view('Config::config_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Config $config)
	{
		$data['config'] = $config;


		$data['forms'] = array(
			'key' => ['label' => 'Key', 'type' => 'text', 'value' => $config->key, 'required' => true, 'id' => 'key'],
			'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea', 'value' => $config->deskripsi, 'required' => true, 'id' => 'deskripsi'],
			'default' => ['label' => 'Default', 'type' => 'text', 'value' => $config->default, 'required' => true, 'id' => 'default'],
			'form_type' => ['label' => 'Form Type', 'type' => 'text', 'value' => $config->form_type, 'required' => true, 'id' => 'form_type'],
			'form_label' => ['label' => 'Form Label', 'type' => 'text', 'value' => $config->form_label, 'required' => true, 'id' => 'form_label'],
			'value' => ['label' => 'Value', 'type' => 'textarea', 'value' => $config->value, 'required' => true, 'id' => 'value'],

		);

		return view('Config::config_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'key' => 'required',
			'deskripsi' => 'required',
			'default' => 'required',
			'form_type' => 'required',
			'form_label' => 'required',
			'value' => 'required',

		]);

		$config = Config::find($id);
		$config->key = $request->input("key");
		$config->deskripsi = $request->input("deskripsi");
		$config->default = $request->input("default");
		$config->form_type = $request->input("form_type");
		$config->form_label = $request->input("form_label");
		$config->value = $request->input("value");

		$config->updated_by = Auth::id();
		$config->save();

		return redirect()->route('config.index')->with('message_success', 'Config berhasil diubah!');
	}

	public function destroy($id)
	{
		$config = Config::find($id);
		$config->deleted_by = Auth::id();
		$config->save();
		$config->delete();

		return back()->with('message_success', 'Config berhasil dihapus!');
	}

}
