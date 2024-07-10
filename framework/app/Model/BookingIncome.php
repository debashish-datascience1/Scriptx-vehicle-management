<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingIncome extends Model {

	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "booking_income";
	protected $fillable = ['booking_id', 'income_id'];

	function booking_income() {
		return $this->hasOne("App\Model\IncomeModel", "id", "income_id")->withTrashed();
	}

}
