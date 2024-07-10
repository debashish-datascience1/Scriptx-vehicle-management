<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kodeine\Metable\Metable;

class Bookings extends Model
{
	use Metable;
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "bookings";
	protected $metaTable = 'bookings_meta';
	protected $fillable = [
		'customer_id', 'vehicle_id', 'user_id', 'pickup', 'dropoff', 'pickup_addr', 'dest_addr', 'travellers', 'status', 'comment', 'dropoff_time', 'driver_id', 'note'
	];

	protected function getMetaKeyName()
	{
		return 'booking_id'; // The parent foreign key
	}

	function scopeCompleted($query)
	{
		return $query->where('status', 1);
	}

	function scopePaidBookings($query)
	{
		return $query->where(['status' => 1, 'payroll_check' => 1]);
	}

	function vehicle()
	{
		return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id")->withTrashed();
	}
	function customer()
	{
		return $this->hasOne("App\Model\User", "id", "customer_id")->withTrashed();
	}

	function driver()
	{
		return $this->hasOne("App\Model\User", "id", "driver_id")->withTrashed();
	}

	function user()
	{
		return $this->hasOne("App\Model\User", "id", "user_id")->withTrashed();
	}

	function transaction_details()
	{
		return $this->belongsTo("App\Model\Transaction", "next_booking", "from_id")->where('param_id', 18)->where(function ($query) {
			return $query->where('advance_for', '!=', 21)
				->orWhere('advance_for', null);
		});
	}

	function transaction_det()
	{
		return $this->belongsTo("App\Model\Transaction", "id", "from_id")->where('param_id', 18)->where(function ($query) {
			return $query->where('advance_for', '!=', 21)
				->orWhere('advance_for', null);
		});
	}

	function advanceToDriver()
	{ // Records From Booking where advance was given.
		// return $this->hasMany("App\Model\AdvanceDriver","booking_id","id");
		return $this->hasOne("App\Model\AdvanceDriver", "booking_id", "id")->where('param_id', 7)->where(function ($query) {
			$query->where("advance_driver.value", '!=', 0)
				->whereRaw("advance_driver.value IS NOT NULL");
		});
	}



	// multivehicle test
	// function test1() {
	// 	return $this->hasMany("App\Model\VehicleModel", "id", "v_id")->withTrashed();
	// }
	// function test() {
	// 	return $this->belongsTo("App\Model\VehicleModel", "v_id", "id")->withTrashed();
	// }
}
