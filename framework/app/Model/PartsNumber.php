<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsNumber extends Model
{
	use SoftDeletes;
	protected $table = "parts_number";
	protected $fillable = ['order_id', 'part_id', 'used_id', 'category_id', 'slno', 'description'];

	public function setSlNoAttribute($value)
	{
		$this->attributes['slno'] = strtoupper(strtolower($value));
	}

	public function part()
	{
		return $this->belongsTo(PartsModel::class, 'part_id');
	}
}
