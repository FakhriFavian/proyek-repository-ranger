<?php

namespace App\Modules\Privilege\Models;

use App\Helpers\UsesUuid;
use App\Modules\Menu\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Privilege extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'privilege';
	protected $fillable   = ['*'];

	public function menu()
	{
		return $this->hasOne(Menu::class, 'id', 'id_menu');
	}
}
