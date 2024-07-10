<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReasonsModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "reasons";
	protected $fillable = ['reason'];
}