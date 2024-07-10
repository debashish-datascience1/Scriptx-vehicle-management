<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;

class Manufacturer extends Model {

    use SoftDeletes;
	protected $table = "manufacturer";
	protected $fillable = ['name'];

    // public function driver(){
	// 	return $this->belongsTo("\App\Model\User",'driver_id')->withTrashed();
	// }

	// public function driver_vehicle(){
	// 	return $this->belongsTo("\App\Model\DriverVehicleModel","driver_id","driver_id");
	// }
}