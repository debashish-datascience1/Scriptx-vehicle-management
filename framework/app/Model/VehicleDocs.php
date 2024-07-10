<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Kodeine\Metable\Metable;

class VehicleDocs extends Model
{
    // use Metable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "vehicle_docs";
    // protected $metaTable = 'vehicles_meta'; //optional.
    protected $fillable = ['vehicle_id','driver_id','amount','vendor_id','param_id','is_renewed','date','till','status','remarks','method','ddno'];

    // public function reviews()
    // {
    //     return $this->hasMany('App\Model\VehicleReviewModel', 'vehicle_id', 'id');
    // }

    public function vehicle()
    {
        return $this->hasOne("App\Model\VehicleModel", "id", "vehicle_id");
    }
    public function drivervehicle()
    {
        return $this->hasOne("App\Model\DriverVehicleModel", "vehicle_id", "vehicle_id");
    }
    public function transaction()
    {
        return $this->hasOne("App\Model\Transaction", "from_id", "id")->where('param_id',35);
    }
    public function document()
    {
        return $this->hasOne("App\Model\Params", "id", "param_id");
    }
    public function method_param()
    {
        return $this->hasOne("App\Model\Params", "id", "method");
    }
    public function vendor()
    {
        return $this->hasOne("App\Model\Vendor", "id", "vendor_id");
    }
}
