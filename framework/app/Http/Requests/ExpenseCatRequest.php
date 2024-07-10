<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseCatRequest extends FormRequest {
	public function authorize() {
		if (Auth::user()->user_type == "S") {
			return true;
		} else {
			abort(404);
		}
	}

	public function rules() {
		return [

			'name' => 'required|unique:expense_cat,name,' . \Request::get("id") . ',id,deleted_at,NULL',
		];
	}
}
