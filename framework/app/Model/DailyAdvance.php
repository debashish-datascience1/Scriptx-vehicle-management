<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyAdvance extends Model {

    use SoftDeletes;
	protected $table = 'daily_advance';
	protected $fillable = ['driver_id','date','amount','remarks','payroll_check','from_id','advance_driver_id'];

    public function driver(){
        return $this->belongsTo("App\Model\User",'driver_id','id');
    }

    public function assigned_vehicle(){
        return $this->belongsTo("App\Model\DriverVehicleModel",'driver_id','driver_id');
    }
    
}
