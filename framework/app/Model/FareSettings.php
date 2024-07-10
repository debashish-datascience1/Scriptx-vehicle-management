<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FareSettings extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = [
		'key_name', 'key_value', 'type_id',
	];
	protected $table = "fare_settings";

	public static function get($key) {

		return ApiSettings::whereName($key)->first()->key_value;

	}
}