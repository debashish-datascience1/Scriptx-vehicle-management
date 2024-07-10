<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'parts';
	protected $fillable = [
		'item','category_id','manufacturer','description','unit','hsn','amount','min_stock','stock'
	];

	function unit_details() {
		return $this->belongsTo("App\Model\UnitModel", "unit")->withTrashed();
	}

	function category() {
		return $this->belongsTo("App\Model\PartsCategoryModel", "category_id")->withTrashed();
	}

	function manufacturer_details() {
		return $this->belongsTo("App\Model\Manufacturer", "manufacturer")->withTrashed();
	}
}