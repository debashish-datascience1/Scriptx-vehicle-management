<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kodeine\Metable\Metable;

class Payroll extends Model
{

    use Metable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "payroll";
    protected $metaTable = 'payroll_meta';
    protected $fillable = ['user_id', 'salary', 'date', 'payable_salary', 'for_month', 'for_year', 'for_date', 'remaining_salary'];

    // public function driver(){
    //     return $this->belongsTo('\App\Model\User','user_id')->withTrashed();
    // }

    public function vehicle()
    {
        return $this->belongsTo('App\Model\VehicleModel', 'vehicle_id');
    }

    function driver_vehicle()
    {
        return $this->belongsTo('App\Model\DriverVehicleModel', 'user_id', 'driver_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function drivers()
    {
        return $this->hasMany(Leave::class, "driver_id", "user_id");
    }

    public function scopeForDateDesc($query)
    {
        $query->orderBy('for_date', 'DESC');
    }
}
