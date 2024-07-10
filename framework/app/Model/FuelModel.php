<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelModel extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $appends = ['fuel_price'];
	protected $table = "fuel";
	protected $fillable = ['vehicle_id', 'user_id', 'start_meter', 'reference', 'provience', 'note', 'qty', 'fuel_from', 'cost_per_unit', 'complete', 'date', 'vendor_name', 'mileage_type', 'cgst', 'sgst', 'cgst_amt', 'sgst_amt', 'is_gst'];

	function getFuelPriceAttribute()
	{
		return $this->attributes['fuel_price'] = bcdiv($this->qty * $this->cost_per_unit, 1, 2);
	}

	function vehicle_data()
	{

		return $this->belongsTo("App\Model\VehicleModel", "vehicle_id", "id")->withTrashed();
	}

	function fuel_details()
	{
		return $this->belongsTo("App\Model\FuelType", "fuel_type");
	}

	function vendor()
	{
		return $this->belongsTo("App\Model\Vendor", 'vendor_name')->withTrashed();
	}
}
