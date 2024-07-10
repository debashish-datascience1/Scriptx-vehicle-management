<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkPayment extends Model {

    use SoftDeletes;
	protected $table = 'bulk_payment';
	protected $fillable = ['bank_id','date','amount','cv_id'];

    public function bank(){
        return $this->belongsTo("App\Model\BankAccount","bank_id",'id');
	}

    public function transaction(){
        return $this->belongsTo("App\Model\Transaction","transaction_id",'id');
    }

    public function bulk_list(){
        return $this->hasMany("App\Model\BulkList","bulk_id","id");
    }

    public function single_bulk_list(){
        return $this->hasOne("App\Model\BulkList","bulk_id","id");
    }

    public function params(){
        return $this->belongsTo("App\Model\Params","param_id");
    }

    public function vendor(){
        return $this->hasOne("App\Model\Vendor","id","cv_id");
    }

    public function customer(){
        return $this->hasOne("App\Model\User","id","cv_id");
    }
    
}
