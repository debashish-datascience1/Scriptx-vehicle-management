<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankTransaction extends Model {

    use SoftDeletes;
	protected $table = 'bank_transactions';
	protected $fillable = ['bank_id','refer_bank','amount','date','remarks','from_id'];

    public function bank(){
        return $this->belongsTo('App\Model\BankAccount','bank_id','id');
    }

    public function referBank(){
        return $this->belongsTo('App\Model\BankAccount','refer_bank','id');
    }
    
}
