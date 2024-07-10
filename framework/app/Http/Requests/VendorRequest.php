<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest {
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
			'name' => 'required',
			'type' => 'required',
			// 'website' => 'required',
			'phone' => 'required',
			// 'address1' => 'required',
			// 'email' => 'required',
			'city' => 'required',
			'postal_code' => 'regex:/\d{5}(-\d{0,4})?/|nullable',
			'country' => 'required',
			'photo' => 'nullable|image|mimes:jpg,png,jpeg',
		];
	}
}
