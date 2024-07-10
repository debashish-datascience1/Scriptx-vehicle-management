<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmiModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "emi";
    protected $fillable = [
        'purchase_id', 'vehicle_id', 'driver_id', 'amount', 'date', 'pay_date', 'bank_id', 'reference_no', 'remarks'
    ];


    public function scopeDescending($query)
    {
        return $query->orderBy('pay_date', 'DESC');
    }

    public function scopeAscending($query)
    {
        return $query->orderBy('pay_date', 'ASC');
    }

    public function vehicle()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, "from_id")->where('param_id', 50);
    }
}
