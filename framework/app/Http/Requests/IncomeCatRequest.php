<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class IncomeCatRequest extends FormRequest {
	public function authorize() {
		if (Auth::user()->user_type == "S") {
			return true;
		} else {
			abort(404);
		}
	}
	public function rules() {
		return [

			'name' => 'required|unique:income_cat,name,' . \Request::get("id") . ',id,deleted_at,NULL',
		];
	}
}
