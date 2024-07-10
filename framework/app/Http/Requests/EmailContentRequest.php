<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class EmailContentRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		if (Auth::user()->user_type == "S") {
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
			'insurance' => 'required_if:i,1',
			'vehicle_licence' => 'required_if:vl,1',
			'driving_licence' => 'required_if:dl,1',
			'registration' => 'required_if:r,1',
			'service_reminder' => 'required_if:sr,1',
		];
	}
}
