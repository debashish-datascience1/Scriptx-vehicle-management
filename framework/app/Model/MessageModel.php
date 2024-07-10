<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageModel extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'message';
	protected $fillable = ['fcm_id', 'user_id', 'message', 'name', 'email'];

	public function user() {
		return $this->hasOne("App\Model\User", "id", "user_id")->withTrashed();
	}
}