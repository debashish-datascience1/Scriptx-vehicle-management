<?php

namespace App\Http\Request;

use App\Helpers\Reply;
use Illuminate\Foundation\Http\FormRequest;

class CoreRequest extends FormRequest {

	protected function formatErrors(\Illuminate\Contracts\Validation\Validator $validator) {
		return Reply::formErrors($validator);
	}

}