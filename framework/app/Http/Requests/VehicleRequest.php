<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{

    public function authorize()
    {
        if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
            return true;
        } else {
            abort(404);
        }
    }

    public function rules()
    {
        return [
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|date|date_format:d-m-Y',
            'engine_type' => 'required',
            'horse_power' => 'integer',
            'color' => 'required',
            // 'lic_exp_date' => 'required|date|date_format:Y-m-d',
            // 'reg_exp_date' => 'required|date|date_format:Y-m-d',
            'license_plate' => 'required|unique:vehicles,license_plate,' . \Request::get("id") . ',id,deleted_at,NULL',
            'int_mileage' => 'required|alpha_num',
            'vehicle_image' => 'nullable|image|mimes:jpg,png,jpeg',
            'average' => 'numeric',
        ];
    }
}
