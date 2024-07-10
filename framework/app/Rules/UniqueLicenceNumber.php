<?php

namespace App\Rules;
use App\Model\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueLicenceNumber implements Rule {

	public function passes($attribute, $value) {
		$license_number = User::meta()
			->where(function ($query) {
				$query->where('users_meta.key', '=', 'license_number')
					->where('users_meta.value', '=', \Request::get('license_number'));
			})->exists();
		if (!$license_number) {
			return true;
		} else {
			return false;
		}
	}

	public function message() {
		return 'The :attribute must be unique.';
	}
}
