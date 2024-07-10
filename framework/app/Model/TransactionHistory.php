<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Kodeine\Metable\Metable;

class TransactionHistory extends Model {

    // use Metable;
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "transaction_history";
	protected $fillable = ['trans_id','new_transid','fault_type','full_amount', 'new_amount','revised_amount','date'];
}