<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class NotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->user_type != "C") {
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
            'vehicle_id' => 'required',
            'customer_id' => 'required',
            'note' => 'required',
            'submitted_on' => 'required|date|date_format:Y-m-d',

        ];
    }
}
