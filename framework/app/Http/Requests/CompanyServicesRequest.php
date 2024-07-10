<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CompanyServicesRequest extends FormRequest {

	public function authorize() {
		return (Auth::user()->user_type == "S");
	}

	public function rules() {
		return [
			'title' => 'required|max:54',
			'description' => 'required|max:93',
			'image' => 'image|mimes:png|dimensions:max_width=90,max_width=90',
		];
	}

	public function messages() {
		return [
			'image.dimensions' => 'Icon Image dimensions must be 90x90.',
		];
	}
}