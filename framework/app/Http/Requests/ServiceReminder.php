<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ServiceReminder extends FormRequest {
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
			'vehicle_id' => 'required',
			'chk' => 'required',
		];
	}

	public function messages() {
		return [
			'vehicle_id.required' => 'select vehicle',
			'chk.required' => 'select atleast one service item',
		];
	}
}
