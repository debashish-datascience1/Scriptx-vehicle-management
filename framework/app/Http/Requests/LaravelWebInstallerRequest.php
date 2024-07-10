<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LaravelWebInstallerRequest extends FormRequest {

	public function authorize() {
		return true;
	}

	public function rules() {
		return [
			'purchase_code' => 'required',
			'hostname' => 'required',
			'username' => 'required',
			'database' => 'required',
		];
	}
}
