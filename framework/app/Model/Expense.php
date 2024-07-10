<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = [
		'vehicle_id', 'user_id', 'amount', 'expense_type', 'comment', 'date', 'exp_id', 'type', 'vendor_id',
	];
	protected $table = "expense";
	function category() {
		return $this->hasOne("App\Model\ExpCats", "id", "expense_type")->withTrashed();
	}

	function service() {
		return $this->hasOne("App\Model\ServiceItemsModel", "id", "expense_type")->withTrashed();
	}

	function vehicle() {
		return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id")->withTrashed();
	}

	function vendor() {
		return $this->hasOne("App\Model\Vendor", "id", "vendor_id")->withTrashed();
	}
}