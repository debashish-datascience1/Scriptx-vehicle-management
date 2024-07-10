<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleGroupModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'vehicle_group';
	protected $fillable = ['name', 'description', 'note'];

	function group() {
		return $this->hasMany("App\Model\VehicleModel", "group_id", "id")->withTrashed();
	}
}