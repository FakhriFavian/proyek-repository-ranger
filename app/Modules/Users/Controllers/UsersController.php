<?php
namespace App\Modules\Users\Controllers;

use Illuminate\Http\Request;
use App\Modules\Role\Models\Role;
use App\Modules\Users\Models\Users;
use App\Http\Controllers\Controller;
use App\Modules\UserRole\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
	protected $title = "Users";
	public function index(Request $request)
	{
		$query = Users::with('roleuser');
		if($request->has('search')){
			$search = $request->get('search');
			$query->where('name', 'like', "%$search%")
					->orWhere('email', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		return view('Users::users', array_merge($data, ['title' => $this->title]));
	}

	public function create()
	{
		$roles = Role::all()->pluck('role', 'id');
		$data['forms'] = array(
			'name' => ['label' => 'Name', 'type' => 'text', 'value' => old('name'), 'required' => true],
			'username' => ['label' => 'Username', 'type' => 'text', 'value' => old('username'), 'required' => true],
			'email' => ['label' => 'Email', 'type' => 'text', 'value' => old('email'), 'required' => true],
			'password' => ['label' => 'Password', 'type' => 'password', 'required' => true],
			'identitas' => ['label' => 'Kode Identitas', 'type' => 'text', 'value' => old('identitas'), 'placeholder' => 'NIM,NIP,NRP,NIK,dll', 'required' => true],
			'roles' => ['label' => 'Role', 'type' => 'select', 'value' => null, 'options' => $roles->all(), 'multiple' => true, 'class' => 'multi-select2', 'required' => true],
		);
		return view('Users::form_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'username' => 'required|unique:users,username',
			'email' => 'required|email',
			'password' => 'required',
			'identitas' => 'required|unique:users,identitas',
			'roles' => 'required|array',
		]);

		$users = new Users();
		$users->name = $request->input("name");
		$users->username = $request->input("username");
		$users->email = $request->input("email");
		$users->password = bcrypt($request->input("password"));
		$users->identitas = $request->input("identitas");
		$users->created_by = Auth::id();
		$users->save();

		foreach ($request->get('roles') as $key => $value) {
			$ur = new UserRole();
			$ur->id_user = $users->id;
			$ur->id_role = $value;
			$ur->save();
		}

		return redirect()->route('users.index')->with('message_success', 'User berhasil ditambahkan!');
	}

	public function edit(Users $user)
	{
		$roles = Role::all()->pluck('role', 'id');
		$selected_roles = UserRole::where('id_user', $user->id)->get()->pluck('id_role');
		$data['selecteds'] = $selected_roles;
		$data['user'] = $user;
		$data['forms'] = array(
			'name' => ['label' => 'Name', 'type' => 'text', 'value' => $user->name, 'required' => true],
			'username' => ['label' => 'Username', 'type' => 'text', 'value' => $user->username, 'required' => true],
			'email' => ['label' => 'Email', 'type' => 'text', 'value' => $user->email, 'required' => true],
			'password' => ['label' => 'Password', 'type' => 'password', 'placeholder' => 'Kosongkan jika tidak ingin mengubah'],
			'identitas' => ['label' => 'Kode Identitas', 'type' => 'text', 'value' => $user->identitas],
			'roles' => ['label' => 'Role', 'type' => 'select', 'value' => $selected_roles->all(), 'options' => $roles->all(), 'multiple' => true, 'class' => 'multi-select2', 'required' => true],
		);

		return view('Users::form_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$validation = array(
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'nullable',
			'username' => 'required',
			'identitas' => 'nullable',
			'roles' => 'required|array',
		);
		$this->validate($request, $validation);

		$users = Users::find($id);
		$users->name = $request->input("name");
		$users->email = $request->input("email");
		$users->password = empty($request->input("password")) ? $users->password : bcrypt($request->input("password"));
		$users->username = $request->input("username");
		$users->identitas = $request->input("identitas");
		$users->updated_by = Auth::user()->id;
		$users->save();

		UserRole::where('id_user', $id)->delete();
		foreach ($request->get('roles') as $key => $value) {
			$ur = UserRole::withTrashed()->where('id_role', $value)->where('id_user', $id)->first();
			$ur = $ur ?? new UserRole();
			$ur->id_role = $value;
			$ur->id_user = $id;
			$ur->deleted_at = NULL;
			$ur->save();
		}

		return redirect()->route('users.index')->with('message_success', 'User berhasil diubah!');
	}

	public function destroy($id)
	{
		$user = Users::find($id);
		$user->deleted_by = Auth::id();
		$user->save();
		$user->delete();

		return back()->with('message_success', 'User berhasil dihapus!');
	}

}
