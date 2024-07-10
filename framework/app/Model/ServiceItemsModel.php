<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceItemsModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'service_items';
	protected $fillable = ['description',
		'time_interval',
		'overdue_time',
		'overdue_unit',
		'meter_interval',
		'overdue_meter',
		'show_time',
		'duesoon_time',
		'duesoon_unit',
		'show_meter',
		'duesoon_meter'];
}