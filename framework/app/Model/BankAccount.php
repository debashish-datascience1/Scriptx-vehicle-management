<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{

	use SoftDeletes;
	protected $table = 'bank_account';
	protected $fillable = ['account_no', 'bank', 'ifsc_code', 'address', 'mobile', 'email', 'status', 'account_holder', 'starting_amount', 'branch'];

	// public function trashData(){

	// }

	public function emi_banks()
	{
		return $this->hasMany(EmiModel::class, 'bank_id');
	}
}
