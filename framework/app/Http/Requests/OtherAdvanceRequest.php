<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OtherAdvanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
    public function rules()
    {
        return [
            "driver_id" => 'required',
            "bank" => 'required',
            "method" => 'required',
            "ref_no" => 'required',
            "date" => 'required',
            "amount" => 'required',
            "remarks" => 'required',
        ];
    }
}
