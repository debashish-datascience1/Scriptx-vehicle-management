<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortageModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "shortages";
	protected $fillable = ['from_id', 'param_id', 'fault_type', 'amount', 'total_amount', 'pay_type', 'date', 'remarks'];

	function vehicle_data() {

		return $this->belongsTo("App\Model\VehicleModel", "vehicle_id", "id")->withTrashed();
	}

	
}