<?php

namespace App\Rules;
use App\Model\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueEId implements Rule {

	public function passes($attribute, $value) {

		$emp_id = User::meta()
			->where(function ($query) use ($value) {
				$query->where('users_meta.key', '=', 'emp_id')
					->where('users_meta.value', '=', $value);
			})->exists();

		if (!$emp_id) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */

	public function message() {
		return 'The :attribute must be unique.';
	}
}
