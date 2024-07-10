<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Model\User;

class AdvanceDriver extends Model {

    // use SoftDeletes;
	protected $table = "advance_driver";
	protected $fillable = ['booking_id','param_id','value','remarks'];

    // public function expenses(){
	// 	return $this->belongsTo("\App\Model\User",'driver_id')->withTrashed();
	// }
    public function param_name(){
        return $this->belongsTo("App\Model\Params",'param_id','id');
    }

    public function booking(){
        return $this->belongsTo('App\Model\Bookings','booking_id');
    }

    public function vehicle(){
        return $this->hasOne('App\Model\VehicleModel','id','vehicle_id');
    }
}