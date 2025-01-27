<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FuelPurchase extends Model
{
    protected $fillable = [
        'date', 
        'vendor_id', 
        'amount', 
        'quantity',
        'remarks',
        'fuel_type_id'


    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}