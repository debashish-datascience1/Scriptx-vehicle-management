<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;

class OtherAdjust extends Model
{

	use SoftDeletes;
	protected $table = "other_adjust";
	protected $fillable = ['other_id', 'head', 'amount', 'method', 'bank_id', 'type', 'ref_no', 'date', 'remarks'];

	public function scopeOrderByName($query)
	{
		return $query->orderBy('head', 'ASC');
	}

	public function driver()
	{
		return $this->belongsTo("\App\Model\User", 'driver_id')->withTrashed();
	}

	public function bank_details()
	{
		return $this->belongsTo("\App\Model\BankAccount", "bank_id");
	}

	public function method_param()
	{
		return $this->belongsTo("\App\Model\Params", "method");
	}

	public function payment_type()
	{
		return $this->belongsTo("\App\Model\Params", "type");
	}

	public function other_advance()
	{
		return $this->belongsTo("\App\Model\OtherAdvance", "other_id");
	}
}
