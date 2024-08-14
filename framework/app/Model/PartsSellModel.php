<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsSellModel extends Model
{
    use SoftDeletes;

    // protected $dates = ['deleted_at'];
	protected $table = 'parts_sell';

    protected $fillable = [
        'sell_to', 'transaction_id',
        'date_of_sell', 'item', 'quantity', 'tyre_numbers',
        'amount', 'total', 'grand_total'
    ];

    protected $dates = ['deleted_at'];
}