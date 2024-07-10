<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest {

	public function authorize() {
		return (Auth::user());
	}

	public function rules() {
		return [
			'excel' => 'required|mimes:xlsx,xls',
		];
	}

	public function messages() {
		return [
			'excel.mimes' => 'File type must be Excel.',
		];
	}
}