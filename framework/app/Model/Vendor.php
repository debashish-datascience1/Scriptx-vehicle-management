<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'vendors';
	protected $fillable = ['name', 'type', 'website', 'note', 'phone', 'address1', 'address2', 'city', 'province', 'email', 'photo', 'udf', 'country', 'postal_code','opening_balance','opening_comment'];
}