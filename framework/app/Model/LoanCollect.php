<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoanCollect extends Model
{
    protected $fillable = ['loan_give_id', 'date', 'amount'];

    public function loanTake()
    {
        return $this->belongsTo(LoanGive::class);
    }
}