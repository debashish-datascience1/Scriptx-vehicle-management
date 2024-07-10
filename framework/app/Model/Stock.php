<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'stocks';
	protected $fillable = [
		'part_id','qty','param_id','from_id'
	];

	// function unit_details() {
	// 	return $this->belongsTo("App\Model\UnitModel", "unit")->withTrashed();
	// }

	// function category() {
	// 	return $this->belongsTo("App\Model\PartsCategoryModel", "category_id")->withTrashed();
	// }

	// function manufacturer_details() {
	// 	return $this->belongsTo("App\Model\Manufacturer", "manufacturer")->withTrashed();
	// }
}