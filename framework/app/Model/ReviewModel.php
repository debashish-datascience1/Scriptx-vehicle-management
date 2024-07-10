<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'reviews';
	protected $fillable = ['user_id', 'booking_id', 'driver_id', 'ratings', 'review_text'];

	public function user() {
		return $this->hasOne("App\Model\User", "id", "user_id")->withTrashed();
	}

}