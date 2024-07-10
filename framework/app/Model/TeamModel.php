<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "team";
	protected $fillable = ['name', 'details', 'designation', 'image'];
}