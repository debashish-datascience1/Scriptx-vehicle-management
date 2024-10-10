<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Wheel;
use Kodeine\Metable\Metable;

class VehicleModel extends Model
{
    use Metable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "vehicles";
    protected $metaTable = 'vehicles_meta'; //optional.
    protected $fillable = ['make', 'wheel', 'model', 'type', 'year', 'engine_type', 'horse_power', 'color', 'license_plate', 'mileage', 'int_mileage', 'in_service', 'user_id', 'insurance_number', 'documents', 'vehicle_image', 'exp_date', 'reg_exp_date', 'lic_exp_date', 'group_id', 'type_id', 'engine_no', 'chassis_no', 'rc_image'];

    protected function getMetaKeyName()
    {
        return 'vehicle_id'; // The parent foreign key
    }

    public function driver()
    {
        return $this->hasOne("App\Model\DriverVehicleModel", "vehicle_id", "id");
    }

    public function vehicles()
    {
        return $this->hasMany(EmiModel::class, "vehicle_id");
    }

    public function income()
    {
        return $this->hasMany("App\Model\IncomeModel", "vehicle_id", "id")->withTrashed();
    }
    public function expense()
    {
        return $this->hasMany("App\Model\Expense", "vehicle_id", "id")->withTrashed();
    }

    public function insurance()
    {
        return $this->hasOne("App\Model\InsuranceModel", "vehicle_id", "id")->withTrashed();
    }

    public function acq()
    {
        return $this->hasMany("App\Model\AcquisitionModel", "vehicle_id", "id");
    }

    public function group()
    {
        return $this->hasOne("App\Model\VehicleGroupModel", "id", "group_id")->withTrashed();
    }

    public function reviews()
    {
        return $this->hasMany('App\Model\VehicleReviewModel', 'vehicle_id', 'id');
    }

    public function types()
    {
        return $this->hasOne("App\Model\VehicleTypeModel", "id", "type_id")->withTrashed();
    }

    public function purchase_info()
    {
        return $this->hasOne(PurchaseInfo::class, "vehicle_id", "id");
    }

    public function scopeGroupType($query, $group_id = null)
    {
        if (!is_null($group_id))
            return $query->where('group_id', $group_id);
        else
            return $query;
    }

    public function bookings()
    {
        return $this->belongsTo(Bookings::class, 'id', 'vehicle_id');
    }

    public function wheel()
    {
        return $this->belongsTo(Wheel::class, 'wheel_id');
    }
}
