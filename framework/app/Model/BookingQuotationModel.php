<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingQuotationModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "booking_quotation";
	protected $fillable = [
		'customer_id', 'vehicle_id', 'user_id', 'pickup', 'dropoff', 'pickup_addr', 'dest_addr', 'travellers', 'status', 'comment', 'dropoff_time', 'driver_id', 'note', 'day', 'mileage', 'waiting_time', 'total', 'tax_total', 'total_tax_percent', 'total_tax_charge_rs',
	];

	function vehicle() {
		return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id")->withTrashed();
	}

	function customer() {
		return $this->hasOne("App\Model\User", "id", "customer_id")->withTrashed();
	}

	function driver() {
		return $this->hasOne("App\Model\User", "id", "driver_id")->withTrashed();
	}

	function user() {
		return $this->hasOne("App\Model\User", "id", "user_id")->withTrashed();
	}

}