<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoanReturn extends Model
{
    protected $fillable = ['loan_take_id', 'date', 'amount'];

    public function loanTake()
    {
        return $this->belongsTo(LoanTake::class);
    }
}