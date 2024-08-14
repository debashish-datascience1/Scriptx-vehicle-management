<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsInvoice extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'parts_invoice';
	protected $fillable = ['user_id','billno','invoice','vendor_id','sub_total','chq_draft_amount','chq_draft_number','chq_draft_date','cash_amount','is_gst','cgst','cgst_amt','sgst','sgst_amt','grand_total','date_of_purchase','tyre_numbers',
	];

	function vendor() {
		return $this->hasOne("App\Model\Vendor", "id", "vendor_id")->withTrashed();
	}

	// function category() {
	// 	return $this->belongsTo("App\Model\PartsCategoryModel", "id", "parts_category")->withTrashed();
	// }
	function partsDetails() {
		return $this->hasMany("App\Model\PartsDetails", "partsinv_id", "id");
	}

	function singlePartsDetail() {
		return $this->hasOne("App\Model\PartsDetails","id","partsinv_id");
	}

	function singledetails() {
		return $this->hasOne("App\Model\PartsDetails","partsinv_id","id");
	}
}