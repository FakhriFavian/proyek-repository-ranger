<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Modules\Menu\Models\Menu;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class GenerateModule extends Command
{
	protected $files;
	protected $signature = 'make:module {name} {--menu} {--withApi}';
	protected $description = 'Make you fuckin crud from just a table name';
	protected $resourcePath;
	private $fields = [];
	private $references = [];

	public function __construct(Filesystem $files)
	{
		parent::__construct();
		$this->files = $files;
		$this->resourcePath = resource_path('stubs');
	}

	public function handle()
	{
		$module = trim($this->argument('name'));
		$this->generate($module);

		$withMenu = $this->option('menu');
		if ($withMenu) {
			Menu::createByModule($module);
		}

		$withApi = $this->option('withApi');
		if ($withApi) {
			if (!array_key_exists('make:api', Artisan::all())) {
				$this->warn('Skip: command make:api tidak tersedia di Laravel versi ini.');
			} else {
				Artisan::call('make:api ' . $module);
			}
		}
	}

	public function generate($module)
	{
		// Ambil nama-nama file
		$name = $this->qualifyClass($module);
		$nameController = $name . 'Controller';
		$nameModel = str_replace('Controllers', 'Models', $name);
		$nameView = str_replace($module . '.', strtolower($module), str_replace('Controllers', 'Views', $name) . '.');
		$nameRoute = str_replace('\\Controllers\\' . $module, '\\routes', $name);

		//Ambil path
		$pathController = $this->getPath($nameController) . '.php';
		$pathModel = $this->getPath($nameModel) . '.php';
		$pathView = $this->getPath($nameView) . '.blade.php';
		$pathDetailView = $this->getPath($nameView) . '_detail.blade.php';
		$pathCreateView = $this->getPath($nameView) . '_create.blade.php';
		$pathUpdateView = $this->getPath($nameView) . '_update.blade.php';
		$pathRoute = $this->getPath($nameRoute) . '.php';


		if ($this->alreadyExists($module)) {
			$this->error($this->type . ' already exists!');
			return false;
		}

		// buat folder
		$this->makeDirectory($pathController);
		$this->makeDirectory($pathModel);
		$this->makeDirectory($pathView);
		$this->makeDirectory($pathRoute);

		// simpan files
		$this->files->put($pathController, $this->buildClassController($nameController));
		$this->files->put($pathModel, $this->buildClassModel($nameModel));
		$this->files->put($pathView, $this->buildClassView($nameView));
		$this->files->put($pathDetailView, $this->buildClassDetailView($nameView));
		$this->files->put($pathCreateView, $this->buildClassFormView($nameView, 'create'));
		$this->files->put($pathUpdateView, $this->buildClassFormView($nameView, 'update'));
		$this->files->put($pathRoute, $this->buildClassRoute($nameRoute, $name, $module));


		$this->info('Hellyeah! ' . $module . ' module was successfully created.');
	}

	protected function qualifyClass($name)
	{
		$rootNamespace = $this->rootNamespace();
		if (Str::startsWith($name, $rootNamespace)) {
			return $name;
		}
		$name = str_replace('/', '\\', $name);
		return $this->qualifyClass($this->getDefaultNamespace(trim($rootNamespace, '\\'), $name) . '\\' . $name);
	}

	protected function rootNamespace()
	{
		return $this->laravel->getNamespace();
	}

	protected function getPath($name)
	{
		$name = Str::replaceFirst($this->rootNamespace(), '', $name);
		return $this->laravel['path'] . '/' . str_replace('\\', '/', $name);
	}

	protected function alreadyExists($rawName)
	{
		return $this->files->exists($this->getPath($this->qualifyClass($rawName)));
	}

	protected function makeDirectory($path)
	{
		if (!$this->files->isDirectory(dirname($path))) {
			$this->files->makeDirectory(dirname($path), 0777, true, true);
		}
		return $path;
	}

	protected function buildClassController($name)
	{
		$stub = $this->files->get($this->resourcePath . '/controller.plain.stub');
		$class = str_replace($this->getNamespace($name) . '\\', '', $name);
		$module = str_replace('Controller', '', $class);
		$slug = strtolower($module);

		$titel = Str::title(str_replace('_', ' ', Str::snake($module)));

		$stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
		$stub = str_replace('DummyRootNamespace', $this->rootNamespace(), $stub);
		$stub = str_replace('DummyClass', $class, $stub);
		$stub = str_replace('selug', $slug, $stub);
		$stub = str_replace('Kelas', $module, $stub);
		$stub = str_replace('Title', $titel, $stub);

		$fields = $this->getTableInfo($module);
		$form_add        = '';
		$form_edit       = '';
		$model_field     = '';
		$column_title    = '';
		$ajax_field      = '';
		$shown_column    = '';
		$form_validation = '';
		$addRef          = '';
		$useRef          = '';
		$counter 	  	 = 0;

		$except_field = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

		foreach ($fields['kolom'] as $key => $value) {
			$form_add .= $this->genInputForm($key, (object) $value, false, $slug);
			$form_edit .= $this->genInputForm($key, (object) $value, true, $slug);

			if ($value['referensi'] != null) {
				$kelasRef = Str::studly($value['referensi']);
				$addRef .= '$ref_' . $value['referensi'] . ' = ' . $kelasRef . "::all()->pluck('" . $value['referensi_desc_kolom'] . "','id');" . PHP_EOL . "		";
				$useRef .= "use App\Modules" . "\\" . $kelasRef . "\Models" . "\\" . $kelasRef . ";" . PHP_EOL;
				$this->references[$key] = $value['referensi'];
			}


			if (!in_array($key, $except_field)) {
				$this->fields[] = $key;
				$label = Str::title(str_replace('_', ' ', str_replace('id_', '', $key)));
				$column_title .= "'" . $label . "', ";
				$ajax_field .= "'" . $key . "', ";
				$shown_column .= $counter . ", ";
				$counter++;
				$model_field .= "$" . $slug . "->" . $key . ' = $request->input("' . $key . '");
		';

				$form_validation .= "'" . $key . "' => 'required'," . PHP_EOL . "			";
			}
		}

		if ($counter < 2) {
			$column_title .= "'Dibuat pada', ";
			$ajax_field .= "'created_at', ";
			$shown_column .= ($counter + 1) . ", ";
		}

		$stub = str_replace('//Forms//', $form_add, $stub);
		$stub = str_replace('//FormsEdit//', $form_edit, $stub);
		$stub = str_replace('//ModelField//', $model_field, $stub);
		$stub = str_replace('"ColumnJudul"', $column_title, $stub);
		$stub = str_replace('"AjaxField"', $ajax_field, $stub);
		$stub = str_replace('"ShownColumn"', $shown_column, $stub);
		$stub = str_replace('//FormValidation//', $form_validation, $stub);
		$stub = str_replace('//FormReference//', $addRef, $stub);
		$stub = str_replace('//ImportReference//', $useRef, $stub);

		return $stub;
	}

	protected function buildClassModel($name)
	{
		$stub = $this->files->get($this->resourcePath . '/model.plain.stub');
		$class = str_replace($this->getNamespace($name) . '\\', '', $name);

		// buat relasi tabel
		$namespaceRelationalTabel = '';
		$relationalTabel = '';
		foreach ($this->fields as $value) {
			if (Str::contains($value, 'id_')) {
				$model = Str::studly($this->references[$value] ?? Str::replace('id_', '', $value));
				$namespaceRelationalTabel .= "use App\Modules\\" . $model . "\Models\\" . $model . ";" . PHP_EOL;
				$relationalTabel .= 'public function ' . Str::camel($model) . '(){
		return $this->belongsTo(' . $model . '::class,"' . $value . '","id");
	}' . PHP_EOL;
			}
		}

		$stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
		$stub = str_replace('DummyRootNamespace', $this->rootNamespace(), $stub);
		$stub = str_replace('DummyClass', $class, $stub);
		$stub = str_replace('NamaModel', Str::snake($class), $stub);
		$stub = str_replace('NamespaceRelationalTabel', $namespaceRelationalTabel, $stub);
		$stub = str_replace('RelationalTabel', $relationalTabel, $stub);

		return $stub;
	}

	protected function buildClassView($name)
	{
		$stub = $this->files->get($this->resourcePath . '/blade.plain.stub');
		$class = str_replace($this->getNamespace($name) . '\\', '', $name);

		$fields = '';
		$columns = '';
		foreach ($this->fields as $key => $value) {
			$isi = Str::of($value)->trim();
			$fields .= "<td>{{ \$item->" . $isi . " }}</td>" . PHP_EOL . "									";
			$columns .= "<td>" . Str::title(str_replace('_', ' ', str_replace('id_', '', $value))) . "</td>" . PHP_EOL . "								";
		}

		$stub = str_replace('NamaModule', strtolower($class), $stub);
		$stub = str_replace('JudulKolom', $columns, $stub);
		$stub = str_replace('IsiKolom', $fields, $stub);
		$stub = str_replace('JmlKolom', count($this->fields) + 2, $stub);
		return $stub;
	}

	protected function buildClassDetailView($name)
	{
		$stub = $this->files->get($this->resourcePath . '/detail.plain.stub');
		$class = str_replace($this->getNamespace($name) . '\\', '', $name);

		$labelData = '';
		$valueData = '';
		$detailData = '';
		foreach ($this->fields as $key => $value) {
			$label = $value;
			if (Str::contains($value, 'id_')) {
				// ubah kalo foreign key ke relation
				$label = str_replace('id_', '', $value);
				$relationName = Str::camel($this->references[$value] ?? $label);
				$value = $relationName . "->id";
			}
			$labelData = "<div class='col-lg-2'><p>" . str_replace('_', ' ', Str::title($label)) . "</p></div>";
			$valueData = "<div class='col-lg-10'><p class='fw-bold'>{{ $" . strtolower($class) . "->$value }}</p></div>";
			$detailData .= $labelData . $valueData . PHP_EOL . "									";
		}

		$stub = str_replace('NamaModule', strtolower($class), $stub);
		$stub = str_replace('DetailData', $detailData, $stub);
		return $stub;
	}

	protected function buildClassFormView($name, $action)
	{
		$stub = $this->files->get($this->resourcePath . '/form.plain.stub');
		$class = str_replace($this->getNamespace($name) . '\\', '', $name);

		$stub = str_replace('NamaModule', strtolower($class), $stub);
		$stub = str_replace('AksiForm', $action == 'create' ? 'Tambah' : 'Edit', $stub);
		$stub = str_replace('FormAction', $action == 'create' ? 'store' : 'update', $stub);
		$stub = str_replace('FormParam', $action == 'create' ? '' : ', $' . strtolower($class) . '->id', $stub);
		$stub = str_replace('FormMethod', $action == 'create' ? '' : "@method('patch')", $stub);

		return $stub;
	}

	protected function buildClassRoute($name, $class, $module)
	{
		$stub = $this->files->get($this->resourcePath . '/route.plain.stub');

		$stub = str_replace('DummyClass', $class . 'Controller', $stub);
		$stub = str_replace('selug', strtolower($module), $stub);
		$stub = str_replace('Kelas', $module, $stub);

		return $stub;
	}

	protected function getNamespace($name)
	{
		return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
	}

	protected function getDefaultNamespace($rootNamespace, $name)
	{
		return $rootNamespace . '\Modules\\' . $name . '\\Controllers';
	}

	private function getTableInfo($namaModel)
	{
		$tabel = Str::snake($namaModel);
		$database = env('DB_DATABASE') == null ? config('database.connections.' . config('database.default') . '.database') : env('DB_DATABASE');
		$ret['tabel'] = $tabel;


		$q = DB::select("select * from information_schema.COLUMNS where TABLE_SCHEMA='" . $database . "' and TABLE_NAME='" . $tabel . "'");

		if (empty($q)) {
			die("Tabel $tabel gak ketemu. \n");
		}

		$primary_key = [];
		$counter_primary = 0;

		foreach ($q as $row) {
			$ret['kolom'][$row->COLUMN_NAME] = [
				'is_nullable' => ($row->IS_NULLABLE == "NO" ? false : true),
				'tipe_data' => $row->DATA_TYPE,
				'length' => $row->CHARACTER_MAXIMUM_LENGTH,
				'catatan' => $row->COLUMN_COMMENT,
				'is_primary' => $row->COLUMN_KEY == "PRI" ? true : false,
				'referensi' => null,
			];

			if ($row->COLUMN_KEY == "PRI") {
				$counter_primary++;
				$ret['primary_key'][] = $row->COLUMN_NAME;
			}
		}

		$q = DB::select("select * from information_schema.KEY_COLUMN_USAGE where TABLE_SCHEMA='" . $database . "' and TABLE_NAME='" . $tabel . "' and REFERENCED_TABLE_NAME is not null");

		foreach ($q as $row) {
			$reference = DB::select("select * from information_schema.COLUMNS where TABLE_SCHEMA='" . $database . "' and TABLE_NAME='" . $row->REFERENCED_TABLE_NAME . "'");

			$ret['kolom'][$row->COLUMN_NAME]['referensi'] = $row->REFERENCED_TABLE_NAME;
			$ret['kolom'][$row->COLUMN_NAME]['referensi_kolom'] = $row->REFERENCED_COLUMN_NAME;
			$ret['kolom'][$row->COLUMN_NAME]['referensi_desc_kolom'] = $reference[1]->COLUMN_NAME;
		}

		return $ret;
	}

	/**
	 * Generate satu baris array field untuk $data['forms'], dikonsumsi oleh <x-dynamic-field>.
	 */
	public function genInputForm($field_name, $attributes, $is_edit = false, $module = NULL)
	{
		$except_field = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
		if (in_array($field_name, $except_field)) return '';

		$label = Str::title(str_replace('_', ' ', str_replace('id_', '', $field_name)));
		$is_required = $attributes->is_nullable ? 'false' : 'true';
		$edit = $is_edit ? "$" . $module . "->" . $field_name : 'old("' . $field_name . '")';

		$type = 'text';
		$extra = '';

		if ($attributes->referensi != null) {
			$type = 'select';
			$extra = ", 'options' => \$ref_" . $attributes->referensi . "->all(), 'class' => 'select2'";
		} else {
			switch ($attributes->tipe_data) {
				case 'integer':
				case 'bigint':
					$type = 'number';
					break;

				case 'text':
					$type = 'textarea';
					break;

				case 'tinyint':
					if (substr($field_name, 0, 3) == "is_") {
						$type = 'select';
						$extra = ", 'options' => ['1' => 'Ya', '0' => 'Tidak']";
					} else {
						$type = 'text';
					}
					break;

				case 'date':
					$type = 'text';
					$extra = ", 'class' => 'datepicker'";
					break;

				case 'datetime':
					$type = 'text';
					$extra = ", 'class' => 'datetimepicker'";
					break;

				case 'varchar':
				case 'char':
				default:
					$type = 'text';
					if (!empty($attributes->catatan)) {
						$extra = ", 'placeholder' => '" . addslashes($attributes->catatan) . "'";
					}
					break;
			}

			if ($field_name == 'urutan') {
				$type = 'number';
				$extra = ", 'placeholder' => 'n'";
			}
		}

		$idAttr = $is_edit ? ", 'id' => '" . $field_name . "'" : '';

		$formBody = "'" . $field_name . "' => ['label' => '" . $label . "', 'type' => '" . $type . "', 'value' => " . $edit . ", 'required' => " . $is_required . $extra . $idAttr . "],
			";

		return $formBody;
	}
}
