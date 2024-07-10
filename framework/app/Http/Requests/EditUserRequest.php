<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest {
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

	public function rules() {
		return [
			'module' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email|unique:users,email,' . \Request::get("id"),
			'profile_image' => 'nullable|image|mimes:jpg,png,jpeg',
		];
	}

	public function messages() {
		return [
			'module.required' => 'You must have to select Permission',

		];
	}
}
