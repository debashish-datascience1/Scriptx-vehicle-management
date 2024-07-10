<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class VehicleGroupRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		// return (Auth::user()->user_type == "S");
		if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
			return true;
		} else {
			abort(404);
		}

	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'name' => 'required',
		];
	}
}
