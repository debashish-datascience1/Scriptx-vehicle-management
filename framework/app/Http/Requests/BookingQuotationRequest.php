<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class BookingQuotationRequest extends FormRequest {

	public function authorize() {
		if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
			return true;
		} else {
			abort(404);
		}

	}

	public function rules() {
		return [
			'customer_id' => 'required',
			'vehicle_id' => 'required',
			'pickup_addr' => 'required',
			'dest_addr' => 'required|different:pickup_addr',
		];
	}

	public function messages() {
		return [
			'dest_addr.different' => 'Pickup address and drop-off address must be different',
		];
	}
}