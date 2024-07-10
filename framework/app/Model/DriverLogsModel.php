<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DriverLogsModel extends Model {

	protected $table = 'driver_logs';
	protected $fillable = ['driver_id', 'vehicle_id', 'date'];

	function vehicle() {
		return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id")->withTrashed();
	}

	function driver() {
		return $this->hasOne("App\Model\User", "id", "driver_id")->withTrashed();
	}
}
