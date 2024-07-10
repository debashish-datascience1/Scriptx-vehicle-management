<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = [
		'key_name', 'key_value',
	];
	protected $table = "frontend";

}