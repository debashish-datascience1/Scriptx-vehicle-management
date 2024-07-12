<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsUsedModel extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "parts_used";
	protected $fillable = ['part_id', 'work_id', 'qty', 'price', 'total', 'is_own', 'cgst', 'cgst_amt', 'sgst', 'sgst_amt', 'grand_total','tyre_used'];

	function part()
	{
		return $this->hasOne("App\Model\PartsModel", "id", "part_id");
	}

	function workorder()
	{
		return $this->hasOne("App\Model\WorkOrders", "id", "work_id")->withTrashed();
	}

	function parts_number()
	{
		return $this->hasMany(PartsNumber::class, "used_id");
	}
}
