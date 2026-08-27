<?php

namespace App\Modules\borrowings\Models;

use App\Helpers\UsesUuid;
use App\Modules\Users\Models\Users;
use App\Modules\borrowing_details\Models\borrowing_details;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class borrowings extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'jam_mulai' => 'datetime', 'jam_selesai' => 'datetime', 'tanggal_approval' => 'datetime', 'tanggal_kembali' => 'datetime'];
	protected $table      = 'borrowings';
	protected $fillable   = ['*'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(Users::class, 'user_id');
	}

	public function details(): HasMany
	{
		return $this->hasMany(borrowing_details::class, 'borrowing_id');
	}
}
