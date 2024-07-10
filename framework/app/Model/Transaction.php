<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Kodeine\Metable\Metable;

class Transaction extends Model {

    // use Metable;
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "transactions";
	protected $fillable = ['transaction_id','type','from_id','param_id', 'total','grandtotal','remaining','date','is_completed','advance_for','bank_id','from_transaction'];

	public function income_expense(){
		return $this->hasOne("App\Model\IncomeExpense","transaction_id","id");
	}

	public function incExp(){
		return $this->hasOne("App\Model\IncomeExpense","transaction_id","id")->orderBy('id','DESC')->take(1);
	}

	public function remainingAmount(){
		return $this->hasMany("App\Model\IncomeExpense","transaction_id","id")->orderBy('id','DESC')->take(1);
	}

	public function getRefNo(){
		return $this->hasOne("App\Model\IncomeExpense","transaction_id","id")->orderBy('id','DESC')->take(1);
	}

	public function params(){
		return $this->belongsTo("App\Model\Params","param_id","id");
	}

	public function pay_type(){
		return $this->belongsTo("App\Model\Params","type","id");
	}

	public function pay_method(){
		return $this->belongsTo("App\Model\Params","method","id");
	}

	public function advancefor(){
		return $this->belongsTo("App\Model\Params","advance_for","id");
	}

	public function booking(){
		return $this->belongsTo("App\Model\Bookings","from_id","id");
	}

	public function payroll(){
		return $this->belongsTo("App\Model\Payroll","from_id","id");
	}

	public function fuel(){
		return $this->belongsTo("App\Model\FuelModel","from_id","id")->withTrashed();
	}

	public function parts(){
		return $this->belongsTo("App\Model\PartsInvoice","from_id","id")->withTrashed();
	}

	public function workorders(){
		return $this->belongsTo("App\Model\WorkOrders","from_id","id")->withTrashed();
	}

	public function bank(){
		return $this->belongsTo("App\Model\BankAccount","bank_id","id")->withTrashed();
	}

	public function vehicle_document(){
		return $this->hasOne("App\Model\VehicleDocs","id","from_id");
	}

	public function filtered_vendor(){
		return $this->hasOne("App\Model\Vendor","id","org_id");
	}

	public function filtered_customer(){
		return $this->hasOne("App\Model\User","id","org_id");
	}

	public function from_transa(){
		return $this->hasOne("App\Model\Transaction","id","from_transaction");
	}

}