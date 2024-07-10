<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyServicesModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['title', 'image', 'description'];
	protected $table = "company_services";
}