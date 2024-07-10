<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'parts';
	protected $fillable = [
		//'image',
		//'barcode',
		'billno',
		//'description',
		//'unit_cost',
		'vendor_id',
		//'manufacturer',
		//'note',
		//'stock',
		//'udf',
		//'category_id',
		'user_id',
		//'status',
		//'availability',
		//'title',
		'sub_total',
		'cash_amount',
		'chq_draft_amount',
		'chq_draft_number',
		'chq_draft_date',
		//'year',
		//'model',
	];

	function vendor() {
		return $this->hasOne("App\Model\Vendor", "id", "vendor_id")->withTrashed();
	}

	// function category() {
	// 	return $this->belongsTo("App\Model\PartsCategoryModel", "id", "parts_category")->withTrashed();
	// }
	function partsDetails() {
		return $this->hasMany("App\Model\PartsDetails", "parts_id", "id");
	}
}