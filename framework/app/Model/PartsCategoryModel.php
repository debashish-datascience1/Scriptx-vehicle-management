<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsCategoryModel extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'parts_category';
	protected $fillable = ['name', 'is_itemno'];
}
