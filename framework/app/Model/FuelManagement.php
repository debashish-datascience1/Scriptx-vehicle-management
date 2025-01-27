<?php


namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FuelManagement extends Model
{
    protected $fillable = [
        'date', 
        'vehicle_id', 
        'quantity', 
        'remark'
    ];

    public function vehicle()
    {
        return $this->belongsTo(VehicleModel::class);
    }
}