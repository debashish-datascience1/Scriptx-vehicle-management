<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fastag extends Model
{
    use SoftDeletes;

    protected $table = 'fastags';

    protected $fillable = [
        'toll_gate_name',
        'amount',
        'fastag',
        'registration_number',
        'date',
        'transaction_id',
        'total_amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public function vehicle()
    {
        return $this->belongsTo(VehicleModel::class);
    }

}