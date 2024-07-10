<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncCats extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['name', 'user_id', 'cost', 'type'];
	protected $table = "income_cat";
	function income() {
		return $this->hasMany("App\Model\IncomeModel", "income_cat", "id")->withTrashed();
	}
}