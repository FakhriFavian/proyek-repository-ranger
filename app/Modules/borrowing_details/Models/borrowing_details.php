<?php

namespace App\Modules\borrowing_details\Models;

use App\Helpers\UsesUuid;
use App\Modules\Items\Models\Items;
use App\Modules\borrowings\Models\borrowings;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class borrowing_details extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'borrowing_details';
	protected $fillable   = ['*'];

	public function borrowing(): BelongsTo
	{
		return $this->belongsTo(borrowings::class, 'borrowing_id');
	}

	public function item(): BelongsTo
	{
		return $this->belongsTo(Items::class, 'item_id')->withTrashed();
	}
}
