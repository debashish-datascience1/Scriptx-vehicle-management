<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehiclReviewRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'vehicle_id' => 'required',
			'reg_no' => 'required',
			'kms_out' => 'required',
			'kms_in' => 'required',
			'datetime_out' => 'required',
			'datetime_in' => 'required',
		];
	}
}
