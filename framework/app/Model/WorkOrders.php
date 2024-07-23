<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrders extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'work_orders';
	protected $fillable = ['vehicle_id', 'category_id', 'vendor_id', 'required_by', 'status', 'description', 'meter', 'note', 'reference', 'price', 'bill_image', 'bill_no','quantity'];

	function vehicle()
	{
		return $this->belongsTo("App\Model\VehicleModel", "vehicle_id", "id")->withTrashed();
	}

	function vendor()
	{
		return $this->belongsTo("App\Model\Vendor", "vendor_id", "id")->withTrashed();
	}

	function order_head()
	{
		return $this->belongsTo(WorkOrderCategory::class, "category_id");
	}

	function parts()
	{
		return $this->hasMany("App\Model\PartsUsedModel", "work_id", "id");
	}
	function parts_fromvendor()
	{
		return $this->hasMany("App\Model\PartsUsedModel", "work_id", "id")->where('is_own', 2);
	}
	function part_fromown()
	{
		return $this->hasMany("App\Model\PartsUsedModel", "work_id", "id")->where('is_own', 1);
	}

	public function part_numbers()
	{
		return $this->hasManyThrough(PartsNumber::class, PartsUsedModel::class, 'work_id', 'used_id');
	}
}
