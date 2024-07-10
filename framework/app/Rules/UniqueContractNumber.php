<?php

namespace App\Rules;
use App\Model\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueContractNumber implements Rule {

	public function passes($attribute, $value) {
		$contract_no = User::meta()
			->where(function ($query) {
				$query->where('users_meta.key', '=', 'contract_number')
					->where('users_meta.value', '=', \Request::get('contract_number'));
			})->exists();
		if (!$contract_no) {
			return true;
		} else {
			return false;
		}
	}

	public function message() {
		return 'The :attribute must be unique.';
	}
}
