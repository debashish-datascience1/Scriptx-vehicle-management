<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotesModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'notes';
	protected $fillable = ['vehicle_id', 'customer_id', 'note', 'submitted_on', 'status'];

	function vehicle() {
		return $this->belongsTo("App\Model\VehicleModel", "vehicle_id", "id")->withTrashed();
	}

	function customer() {
		return $this->belongsTo("App\Model\User", "customer_id", "id")->withTrashed();
	}

}