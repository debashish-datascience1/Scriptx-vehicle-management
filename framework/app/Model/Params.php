<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;

class Params extends Model {

    // use SoftDeletes;
	protected $table = "params";
	protected $fillable = ['label','code'];

    
}