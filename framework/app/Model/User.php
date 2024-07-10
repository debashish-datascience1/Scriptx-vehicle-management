<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kodeine\Metable\Metable;

class User extends Authenticatable
{
	use Metable;
	use SoftDeletes;
	use Notifiable;
	protected $dates = ['deleted_at'];
	protected $table = "users";
	protected $metaTable = 'users_meta'; //optional.
	protected $fillable = [
		'name', 'email', 'password', 'user_type', 'group_id', 'api_token', 'gstin'
	];

	protected $hidden = ['password', 'remember_token', 'api_token'];

	protected function getMetaKeyName()
	{
		return 'user_id'; // The parent foreign key
	}

	public function drivers()
	{
		return $this->hasMany(EmiModel::class, 'driver_id');
	}

	public function user_data()
	{
		return $this->hasMany("App\Model\UserData", 'user_id', 'id')->withTrashed();
	}

	public function vehicle_detail()
	{
		return $this->hasMany('App\Model\VehicleModel', 'user_id', 'id')->withTrashed();
	}

	public function driver_vehicle()
	{
		return $this->hasOne("App\Model\DriverVehicleModel", "driver_id", "id");
	}
	public function driver_booking()
	{
		return $this->hasMany("App\Model\Bookings", 'driver_id', 'id');
	}

	public function scopeGetDrivers($query)
	{
		return $query->whereUserType('D');
	}

	public function scopeGetCustomers($query)
	{
		return $query->whereUserType('C');
	}

	public function scopeGetOthers($query)
	{
		return $query->whereUserType('O');
	}

	public function scopeHaveSalary($query)
	{
		return $query->meta()->where(function ($q) {
			$q->whereRaw("users_meta.key = 'salary' AND users_meta.value IS NOT NULL");
		});
	}

	public function scopeOrderByName($query)
	{
		return $query->orderBy('name', 'ASC');
	}
}
