<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelType extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "fuel_type";
	protected $fillable = ['fuel_name'];

}