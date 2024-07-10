<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrderCategory extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'work_order_categories';
	protected $fillable = ['name', 'description', 'status'];

	public function getStatusAttribute($attribute)
	{
		return $this->statusOptions()[$attribute];
	}

	public function setNameAttribute($value)
	{
		$this->attributes['name'] = ucwords($value);
	}

	public function scopeActive($query)
	{
		return $query->where('status', 1);
	}

	public function scopeDescending($query)
	{
		return $query->orderBy('id', 'DESC');
	}

	public function statusOptions()
	{
		return [
			1 => 'Active',
			0 => 'Inactive',
			// 2 => 'Others',
		];
	}
}
