<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;

class OtherAdvance extends Model {

    use SoftDeletes;
	protected $table = "other_advances";
	protected $fillable = ["driver_id","amount","date","bank","method","ref_no","remarks","is_adjusted"];

    public function driver(){
		return $this->belongsTo("\App\Model\User",'driver_id')->withTrashed();
	}

	public function bank_details(){
		return $this->belongsTo("\App\Model\BankAccount","bank");
	}

	public function method_param(){
		return $this->belongsTo("\App\Model\Params","method");
	}

	public function adjust_advance(){
		return $this->hasMany("\App\Model\OtherAdjust","other_id");
	}
}