<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest {
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
			'name.*' => 'required',
			'icon_img' => 'image|mimes:jpg,png,gif,jpeg',
			'logo_img' => 'image|mimes:jpg,png,gif,jpeg',
		];
	}
}
