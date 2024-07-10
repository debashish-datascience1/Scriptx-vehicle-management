<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeExpense extends Model
{

	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "income_expense";
	protected $fillable = ['transaction_id', 'payment_method', 'date', 'amount', 'remaining', 'remarks', 'faulty', 'ref_no'];

	public function method()
	{
		return $this->belongsTo("App\Model\Params", "payment_method", "id");
	}
	public function transaction()
	{
		return $this->belongsTo("App\Model\Transaction", "transaction_id");
	}

	public function transactions_credit()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('type', 23); //credit
	}

	public function transactions_debit()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('type', 24); //debit
	}

	public function bookings()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('param_id', 18); //bookings
	}
	public function payroll()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('param_id', 19); //payroll
	}
	public function fuel()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('param_id', 20); //fuel
	}
	public function salary_advance()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('param_id', 25); //salary_advance
	}
	public function parts_invoice()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('param_id', 26); //parts_invoice
	}
	public function work_order()
	{
		return $this->belongsTo(Transaction::class, "transaction_id", 'id')->where('param_id', 28); //work_order
	}
}
