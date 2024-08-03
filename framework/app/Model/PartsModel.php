<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartsModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'parts';
	protected $fillable = [
		'item','category_id','manufacturer','description','unit','hsn','amount','min_stock','stock','tyre_numbers','tyres_used'
	];

	function unit_details() {
		return $this->belongsTo("App\Model\UnitModel", "unit")->withTrashed();
	}

	function category() {
		return $this->belongsTo("App\Model\PartsCategoryModel", "category_id")->withTrashed();
	}

	function manufacturer_details() {
		return $this->belongsTo("App\Model\Manufacturer", "manufacturer")->withTrashed();
	}

	public function isTyreCategory()
    {
        $category = $this->category;
        if ($category) {
            return stripos($category->name, 'tyre') !== false;
        }
        return false;
    }

    // Add a mutator for tyre_numbers and tyres_used
    public function setTyreNumbersAttribute($value)
    {
        $this->attributes['tyre_numbers'] = $this->isTyreCategory() ? $value : null;
    }

    public function setTyresUsedAttribute($value)
    {
        $this->attributes['tyres_used'] = $this->isTyreCategory() ? $value : null;
    }
}