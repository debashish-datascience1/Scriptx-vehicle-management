<?php

namespace App\Http\Requests;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class InsuranceRequest extends FormRequest {
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
			'insurance_number'=>'required_without_all:fitness_tax,road_tax,permit_number,pollution_tax',
			'fitness_tax'=>'required_without_all:insurance_number,road_tax,permit_number,pollution_tax',
			'road_tax'=>'required_without_all:fitness_tax,insurance_number,permit_number,pollution_tax',
			'permit_number'=>'required_without_all:fitness_tax,road_tax,insurance_number,pollution_tax',
			'pollution_tax'=>'required_without_all:fitness_tax,road_tax,permit_number,insurance_number',
			'documents' => 'mimes:doc,pdf,docx,jpg,png,jpeg',
			'fitness_taxdocs' => 'mimes:doc,pdf,docx,jpg,png,jpeg',
			'road_docs' => 'mimes:doc,pdf,docx,jpg,png,jpeg',
			'permit_docs' => 'mimes:doc,pdf,docx,jpg,png,jpeg',
			'pollution_docs' => 'mimes:doc,pdf,docx,jpg,png,jpeg',
			'fasttag_docs' => 'mimes:doc,pdf,docx,jpg,png,jpeg',
		];
	}
}
