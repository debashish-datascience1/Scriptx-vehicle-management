<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceReminder;
use App\Model\ServiceItemsModel;
use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\VehicleModel;
use Auth;
use DB;
use Helper;
use Illuminate\Http\Request;

class ServiceReminderController extends Controller
{

	public function index()
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$vehicle_ids = VehicleModel::pluck('id')->toArray();
		} else {
			$vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
		}
		$data['service_reminder'] = ServiceReminderModel::whereIn('vehicle_id', $vehicle_ids)->get();
		return view('service_reminder.index', $data);
	}

	public function create()
	{
		$data['services'] = ServiceItemsModel::get();
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::whereIn_service("1")->get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
		}
		return view('service_reminder.create', $data);
	}

	public function store(ServiceReminder $request)
	{
		$users = User::where('user_type', 'S')->get();
		foreach ($request->get('chk') as $item) {

			$history = ServiceReminderModel::whereVehicleId($request->get('vehicle_id'))->where('service_id', $item)->orderBy('id', 'desc')->first();
			if ($history == null) {
				$last_date = "N/D";
				$last_meter = "0";
			} else {
				$interval = substr($history->services->overdue_unit, 0, -3);
				$int = $history->services->overdue_time . $interval;
				$date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))));
				$last_date = $date;
				if ($history->last_meter == 0) {
					$total = $history->vehicle->int_mileage;
				} else {
					$total = $history->last_meter;
				}
				$last_meter = $total + $history->services->overdue_meter;
			}
			$reminder = new ServiceReminderModel();
			$reminder->vehicle_id = $request->get('vehicle_id');
			$reminder->service_id = $item;
			$reminder->last_date = $request->start_date;
			$reminder->last_meter = $last_meter;
			$reminder->save();
		}

		return redirect()->route('service-reminder.index');
	}

	public function destroy(Request $request)
	{
		ServiceReminderModel::find($request->get('id'))->delete();
		return redirect()->route('service-reminder.index');
	}

	public function bulk_delete(Request $request)
	{
		ServiceReminderModel::whereIn('id', $request->ids)->delete();
		return back();
	}

	public function show()
	{
		$vehicles = DB::raw(DB::select("select id, CONCAT(make,'-',model,'-',license_plate) as name from vehicles where deleted_at IS NULL"))->toArray();
		dd($vehicles);
	}
}
