<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ServiceItem extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
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
			'description' => 'required',
			'time1' => 'required_if:chk1,on',
			'time2' => 'required_if:chk3,on',

		];
	}

	public function messages() {
		return [
			'description.required' => 'description must be required',
			'time1.required_if' => 'Overdue time interval must be required',
			'time2.required_if' => 'Due soon time interval is required',
		];
	}
}
