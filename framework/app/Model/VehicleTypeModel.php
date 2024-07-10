<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleTypeModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'vehicle_types';
	// protected $fillable = ['vehicletype', 'displayname', 'icon', 'isenable', 'seats'];
	protected $fillable = ['vehicletype', 'displayname', 'icon', 'isenable'];
}