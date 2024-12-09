<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FuelBalance extends Model
{
    protected $table = 'fuel_balance';
    
    protected $fillable = [
        'month', 'year', 'vehicle_id', 'balance', 
        'month_end_balance'
    ];
}