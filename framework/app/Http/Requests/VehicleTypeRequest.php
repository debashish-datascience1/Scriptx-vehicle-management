<?php

namespace App\Http\Requests;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class VehicleTypeRequest extends FormRequest {
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
			'vehicletype' => 'required|unique:vehicle_types,vehicletype,' . \Request::get("id") . ',id,deleted_at,NULL',
			'displayname' => 'required',
			'icon' => 'nullable|image|mimes:jpg,png,jpeg',
		];
	}

	public function messages() {
		return [
			'vehicletype.unique' => 'Vehicle type already exist',
		];
	}
}