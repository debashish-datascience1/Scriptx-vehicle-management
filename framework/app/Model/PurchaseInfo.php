<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Kodeine\Metable\Metable;

class PurchaseInfo extends Model
{
    // use Metable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "vehicle_purchaseinfo";
    // protected $metaTable = 'vehicles_meta'; //optional.
    protected $fillable = [
        'vehicle_id', 'purchase_date', 'vehicle_cost', 'amount_paid', 'bank', 'method', 'reference_no', 'loan_date', 'loan_amount', 'bank_name', 'loan_account', 'emi_date', 'emi_amount', 'loan_duration', 'duration_unit', 'loan_about', 'user_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function bank_details()
    {
        return $this->belongsTo(BankAccount::class, 'bank');
    }

    public function method_details()
    {
        return $this->belongsTo(Params::class, 'method');
    }
}
