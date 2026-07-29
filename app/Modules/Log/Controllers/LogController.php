<?php
namespace App\Modules\Log\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Log\Models\Log;
use App\Modules\Users\Models\Users;

class LogController extends Controller
{
	protected $title = "Log";
	public function index(Request $request)
	{
		$query = Log::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();
		return view('Log::log', array_merge($data, ['title' => $this->title]));
	}

	public function create()
	{
		$ref_users = Users::all()->pluck('name','id');

		$data['forms'] = array(
			'id_user' => ['label' => 'User', 'type' => 'select', 'value' => null, 'options' => $ref_users->all(), 'class' => 'select2'],
			'aktivitas' => ['label' => 'Aktivitas', 'type' => 'text', 'value' => old('aktivitas'), 'required' => true],
			'route' => ['label' => 'Route', 'type' => 'text', 'value' => old('route'), 'required' => true],
			'action' => ['label' => 'Action', 'type' => 'text', 'value' => old('action'), 'required' => true],
			'context' => ['label' => 'Context', 'type' => 'text', 'value' => old('context'), 'required' => true],
			'data' => ['label' => 'Data', 'type' => 'text', 'value' => old('data'), 'required' => true],

		);
		return view('Log::log_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_user' => 'required',
			'aktivitas' => 'required',
			'route' => 'required',
			'action' => 'required',
			'context' => 'required',
			'data' => 'required',

		]);

		$log = new Log();
		$log->id_user = $request->input("id_user");
		$log->aktivitas = $request->input("aktivitas");
		$log->route = $request->input("route");
		$log->action = $request->input("action");
		$log->context = $request->input("context");
		$log->data = $request->input("data");

		$log->created_by = Auth::id();
		$log->save();

		return redirect()->route('log.index')->with('message_success', 'Log berhasil ditambahkan!');
	}

	public function show(Log $log)
	{
		$data['log'] = $log;

		return view('Log::log_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Log $log)
	{
		$data['log'] = $log;

		$ref_users = Users::all()->pluck('name','id');

		$data['forms'] = array(
			'id_user' => ['label' => 'User', 'type' => 'select', 'value' => $log->id_user, 'options' => $ref_users->all(), 'class' => 'select2'],
			'aktivitas' => ['label' => 'Aktivitas', 'type' => 'text', 'value' => $log->aktivitas, 'required' => true, 'id' => 'aktivitas'],
			'route' => ['label' => 'Route', 'type' => 'text', 'value' => $log->route, 'required' => true, 'id' => 'route'],
			'action' => ['label' => 'Action', 'type' => 'text', 'value' => $log->action, 'required' => true, 'id' => 'action'],
			'context' => ['label' => 'Context', 'type' => 'text', 'value' => $log->context, 'required' => true, 'id' => 'context'],
			'data' => ['label' => 'Data', 'type' => 'text', 'value' => $log->data, 'required' => true, 'id' => 'data'],

		);

		return view('Log::log_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_user' => 'required',
			'aktivitas' => 'required',
			'route' => 'required',
			'action' => 'required',
			'context' => 'required',
			'data' => 'required',

		]);

		$log = Log::find($id);
		$log->id_user = $request->input("id_user");
		$log->aktivitas = $request->input("aktivitas");
		$log->route = $request->input("route");
		$log->action = $request->input("action");
		$log->context = $request->input("context");
		$log->data = $request->input("data");

		$log->updated_by = Auth::id();
		$log->save();

		return redirect()->route('log.index')->with('message_success', 'Log berhasil diubah!');
	}

	public function destroy($id)
	{
		$log = Log::find($id);
		$log->deleted_by = Auth::id();
		$log->save();
		$log->delete();

		return back()->with('message_success', 'Log berhasil dihapus!');
	}

}
