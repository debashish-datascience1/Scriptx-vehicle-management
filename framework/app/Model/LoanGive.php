<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanGive extends Model
{
    use SoftDeletes;

    protected $fillable = ['date', 'from', 'amount', 'remaining_amount'];

    protected $dates = ['deleted_at'];

    public function returns()
    {
        return $this->hasMany(LoanCollect::class);
    }
}