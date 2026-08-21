<?php

namespace App\Modules\Users\Models;

use App\Helpers\Format;
use App\Helpers\UsesUuid;
use App\Modules\Role\Models\Role;
use App\Modules\borrowings\Models\borrowings;
use Illuminate\Database\Eloquent\Model;
use App\Modules\UserRole\Models\UserRole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Users extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $table      = 'users';
	protected $fillable   = ['*'];

	protected function createdAt(): Attribute
    {
        return Attribute::make(
            function($value){
				return Format::tanggal($value);
			});
    }

	public function roleuser()
	{
		return $this->hasManyThrough(Role::class, UserRole::class, 'id_user', 'id', 'id', 'id_role');
	}

	public function borrowings(): HasMany
	{
		return $this->hasMany(borrowings::class, 'user_id');
	}
}
