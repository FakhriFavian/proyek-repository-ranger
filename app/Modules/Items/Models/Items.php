<?php

namespace App\Modules\Items\Models;

use App\Helpers\UsesUuid;
use App\Modules\categories\Models\categories;
use App\Modules\borrowing_details\Models\borrowing_details;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Items extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = [
		'deleted_at' => 'datetime',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
		'stok_total' => 'integer',
		'stok_tersedia' => 'integer',
	];
	protected $table      = 'items';
	protected $fillable   = ['*'];

	public function category(): BelongsTo
	{
		return $this->belongsTo(categories::class, 'category_id');
	}

	public function borrowingDetails(): HasMany
	{
		return $this->hasMany(borrowing_details::class, 'item_id');
	}
}
