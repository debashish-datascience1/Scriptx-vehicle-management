<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\VehicleModel;
use Illuminate\Http\Request;

class AcquisitionController extends Controller {

	public function destroy(Request $request) {

		$vehicle = VehicleModel::where('id', $request->get("vehicle_id"))->first();
		// dd($vehicle);
		$all = unserialize($vehicle->getMeta('purchase_info'));
		$index = $request->get('key');
		unset($all[$index]);
		// dd($all);
		$vehicle->purchase_info = serialize($all);
		$vehicle->save();
		return view("acquisition.ajax", compact('vehicle'));

	}
	public function store(Request $request) {

		$vehicle = VehicleModel::find($request->get("vehicle_id"));
		$old = array();
		if ($vehicle->getMeta('purchase_info') != "" || $vehicle->getMeta('purchase_info') != null) {
			$old = unserialize($vehicle->getMeta('purchase_info'));
		}
		$array1 = ['exp_name' => $request->get('exp_name'), 'exp_amount' => $request->get('exp_amount')];

		array_push($old, $array1);
		$vehicle->purchase_info = serialize($old);
		$vehicle->save();

		return view("acquisition.ajax", compact('vehicle'));
	}

}
