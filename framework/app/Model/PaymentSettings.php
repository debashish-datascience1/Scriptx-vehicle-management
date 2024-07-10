<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSettings extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = [
		'name', 'value',
	];
	protected $table = "payment_settings";

	public static function get($key) {

		return PaymentSettings::whereName($key)->first()->value;

	}
}
