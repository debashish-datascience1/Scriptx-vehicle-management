<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanTake extends Model
{
    use SoftDeletes;

    protected $fillable = ['date', 'from', 'amount', 'remaining_amount'];

    protected $dates = ['deleted_at'];

    public function returns()
    {
        return $this->hasMany(LoanReturn::class);
    }
}