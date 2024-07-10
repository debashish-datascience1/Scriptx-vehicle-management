<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsDetails extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'parts_details';
	protected $fillable = ['parts_id','partsinv_id','title','parts_category','number','manufacture','status','unit_cost','availability','quantity','date_of_purchase','total'];

	function parts_zero() {
		return $this->belongsTo("App\Model\PartsModel", "parts_id")->withTrashed();
	}

	function parts_invoice(){
		return $this->hasOne("App\Model\PartsInvoice","id","partsinv_id");
	}

	function category() {
		return $this->belongsTo("App\Model\PartsCategoryModel", "parts_category", "id")->withTrashed();
	}
}