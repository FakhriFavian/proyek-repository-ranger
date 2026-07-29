<?php
namespace App\Modules\Menu\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Menu\Models\Menu;
use App\Modules\Role\Models\Role;

use App\Http\Controllers\Controller;
use App\Modules\Log\Models\Log;
use Illuminate\Support\Facades\Auth;
use App\Modules\Privilege\Models\Privilege;

class MenuController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Menu";

	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Menu::with('menuref');
		if($request->has('search')){
			$search = $request->get('search');
			$query->where('menu', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10);

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Menu::menu', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$menus = Menu::where('level', '<', 2)->get()->pluck('menu', 'id');
		$roles = Role::all()->pluck('role', 'id');
		$data['forms'] = array(
			'menu' => ['label' => 'Menu', 'type' => 'text', 'value' => old('menu'), 'placeholder' => 'Nama Menunya'],
			'icon' => ['label' => 'Icon', 'type' => 'text', 'value' => old('icon'), 'placeholder' => 'Icon menu, ex: fa-book'],
			'is_tampil' => ['label' => 'Tampil?', 'type' => 'select', 'value' => old('is_tampil'), 'options' => ['1' => 'Ya', '0' => 'Tidak']],
			'level' => ['label' => 'Level Hirarki', 'type' => 'select', 'value' => null, 'options' => ['0' => 'Group Menu', '1' => 'Menu', '2' => 'Submenu'], 'placeholder' => '-- Pilih Level Menu', 'class' => 'select2'],
			'parent_id' => ['label' => 'Parent Id', 'type' => 'select', 'value' => null, 'options' => $menus->all(), 'placeholder' => '-- Pilih Parent Menu', 'class' => 'select2'],
			'module' => ['label' => 'Module', 'type' => 'text', 'value' => old('module'), 'placeholder' => 'Ex: user'],
			'routing' => ['label' => 'Routing', 'type' => 'text', 'value' => old('routing'), 'placeholder' => 'Ex: user.index'],
			'urutan' => ['label' => 'Urutan', 'type' => 'number', 'value' => old('urutan'), 'placeholder' => 'n'],
			'roles' => ['label' => 'Role', 'type' => 'select', 'value' => null, 'options' => $roles->all(), 'multiple' => true, 'class' => 'multi-select2', 'placeholder' => 'Role', 'required' => true],
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Menu::menu_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'icon' => 'required',
			'is_tampil' => 'required',
			'level' => 'required',
			'menu' => 'required',
			'module' => 'required',
			'parent_id' => 'required',
			'routing' => 'required',
			'urutan' => 'required',
			'roles' => 'required|array',

		]);

		$menu = new Menu();
		$menu->icon = $request->input("icon");
		$menu->is_tampil = $request->input("is_tampil");
		$menu->level = $request->input("level");
		$menu->menu = $request->input("menu");
		$menu->module = $request->input("module");
		$menu->parent_id = $request->input("parent_id");
		$menu->routing = $request->input("routing");
		$menu->urutan = $request->input("urutan");
		$menu->created_by = Auth::id();
		$menu->save();

		$roles = Role::all();
		foreach ($roles as $key => $value) {
			$def = in_array($value->id, $request->get('roles')) ? 1 : 0;

			$priv = Privilege::whereIdMenu($menu->id)->whereIdRole($value->id)->first() ?? new Privilege();
			$priv->id_menu = $menu->id;
			$priv->id_role = $value->id;
			$priv->create = $def;
			$priv->read = $def;
			$priv->show = $def;
			$priv->update = $def;
			$priv->delete = $def;
			$priv->show_menu = $def;
			$priv->save();
		}

		$this->log($request, 'membuat '.$this->title.' baru: '.$menu->menu, ['menu.id' => $menu->id]);
		return redirect()->route('menu.index')->with('message_success', 'Menu berhasil ditambahkan!');
	}

	public function edit(Request $request, Menu $menu)
	{
		$data['menu'] = $menu;
		$roles = Role::all()->pluck('role', 'id');
		$selected = Privilege::where('id_menu', $menu->id)->where('show_menu', 1)->get()->pluck('id_role');
		$menus = Menu::where('level', '<', 2)->get()->pluck('menu', 'id');
		$data['selecteds'] = $selected;
		$data['forms'] = array(
			'menu' => ['label' => 'Menu', 'type' => 'text', 'value' => $menu->menu, 'id' => 'menu'],
			'module' => ['label' => 'Module', 'type' => 'text', 'value' => $menu->module, 'id' => 'module'],
			'icon' => ['label' => 'Icon', 'type' => 'text', 'value' => $menu->icon, 'id' => 'icon'],
			'is_tampil' => ['label' => 'Tampilkan Menu?', 'type' => 'select', 'value' => $menu->is_tampil, 'options' => ['1' => 'Ya', '0' => 'Tidak'], 'id' => 'is_tampil'],
			'level' => ['label' => 'Level Hirarki', 'type' => 'select', 'value' => $menu->level, 'options' => ['0' => 'Group Menu', '1' => 'Menu', '2' => 'Submenu'], 'placeholder' => '-- Pilih Level Menu', 'class' => 'select2'],
			'parent_id' => ['label' => 'Parent Id', 'type' => 'select', 'value' => $menu->parent_id, 'options' => $menus->all(), 'id' => 'parent_id', 'class' => 'select2'],
			'routing' => ['label' => 'Routing', 'type' => 'text', 'value' => $menu->routing, 'id' => 'routing'],
			'urutan' => ['label' => 'Urutan', 'type' => 'text', 'value' => $menu->urutan, 'placeholder' => 'n', 'id' => 'urutan'],
			'roles' => ['label' => 'Role', 'type' => 'select', 'value' => null, 'options' => $roles->all(), 'multiple' => true, 'class' => 'multi-select2', 'placeholder' => 'Role', 'required' => true],
		);

		$this->log($request, 'membuka form edit '.$this->title.' '.$menu->menu, ['menu.id' => $menu->id]);
		return view('Menu::menu_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'icon' => 'required',
			'is_tampil' => 'required',
			'level' => 'required',
			'menu' => 'required',
			'module' => 'required',
			'parent_id' => 'required',
			'routing' => 'required',
			'urutan' => 'required',

		]);

		$menu = Menu::find($id);
		$menuorg = $menu->toArray();
		$menu->icon = $request->input("icon");
		$menu->is_tampil = $request->input("is_tampil");
		$menu->level = $request->input("level");
		$menu->menu = $request->input("menu");
		$menu->module = $request->input("module");
		$menu->parent_id = $request->input("parent_id");
		$menu->routing = $request->input("routing");
		$menu->urutan = $request->input("urutan");
		$menu->updated_by = Auth::id();
		$menu->save();

		$roles = Role::all();
		foreach ($roles as $key => $value) {
			$def = in_array($value->id, $request->get('roles')) ? 1 : 0;

			$priv = Privilege::whereIdMenu($menu->id)->whereIdRole($value->id)->first() ?? new Privilege();
			$priv->id_menu = $menu->id;
			$priv->id_role = $value->id;
			$priv->create = $def;
			$priv->read = $def;
			$priv->show = $def;
			$priv->update = $def;
			$priv->delete = $def;
			$priv->show_menu = $def;
			$priv->save();
		}

		$this->log($request, 'mengedit '.$this->title.' '.$menu->menu, ['menu.id' => $menu->id], ['from' => $menuorg, 'to' => $menu]);
		return redirect()->route('menu.index')->with('message_success', 'Menu berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$menu = Menu::find($id);
		$menu->deleted_by = Auth::id();
		$menu->save();
		$menu->delete();

		$this->log($request, 'menghapus '.$this->title.' '.$menu->menu, ['menu.id' => $menu->id]);
		return back()->with('message_success', 'Menu berhasil dihapus!');
	}

}
