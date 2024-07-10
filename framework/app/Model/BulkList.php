<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkList extends Model {

    use SoftDeletes;
	protected $table = 'bulk_list';
	protected $fillable = ['bulk_id','bank_id','transaction_id','amount','fault','comment'];

    public function bank(){
        return $this->belongsTo("App\Model\BankAccount","bank_id",'id');
	}

    public function transaction(){
        return $this->belongsTo("App\Model\Transaction","transaction_id",'id');
    }
    
}
