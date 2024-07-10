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

class ServiceReminderController extends Controller {

	public function index() {
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$vehicle_ids = VehicleModel::pluck('id')->toArray();
		} else {
			$vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
		}
		$data['service_reminder'] = ServiceReminderModel::whereIn('vehicle_id', $vehicle_ids)->get();
		return view('service_reminder.index', $data);
	}

	public function create() {
		$data['services'] = ServiceItemsModel::get();
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::whereIn_service("1")->get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
		}
		return view('service_reminder.create', $data);
	}

	public function store(ServiceReminder $request) {
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

	public function destroy(Request $request) {
		ServiceReminderModel::find($request->get('id'))->delete();
		return redirect()->route('service-reminder.index');
	}

	public function bulk_delete(Request $request) {
		ServiceReminderModel::whereIn('id', $request->ids)->delete();
		return back();
	}

	public function show(){
		$vehicles = DB::raw(DB::select("select id, CONCAT(make,'-',model,'-',license_plate) as name from vehicles where deleted_at IS NULL"))->toArray();
		dd($vehicles);
	}

	public function report(){
		$data['vehicles'] = VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name','id');
		// $data['veheicles'] =  collect(DB::raw(DB::select("select id, CONCAT(make,'-',model,'-',license_plate) as name from vehicles where deleted_at IS NULL")))->toArray();
		$data['request'] = null;
		// dd($data);
		return view('service_reminder.report', $data);

	}

	public function report_post(Request $request){
		// dd($request->all());
		// dd("Post");
		// dd("Drivers Post Report");
		// dd($request->all());
		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['vehicles'] = VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name','id');
		if($request->get('date1')==null)
			$start = ServiceReminderModel::orderBy('last_date','ASC')->take(1)->first('last_date')->last_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = ServiceReminderModel::orderBy('last_date','DESC')->take(1)->first('last_date')->last_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d',strtotime($start));
		$end = date('Y-m-d',strtotime($end));
		
		

		if(!empty($vehicle_id))
			$data['services'] = ServiceReminderModel::where('vehicle_id',$vehicle_id)->whereBetween('last_date',[$start,$end])->get();
		else
			$data['services'] = ServiceReminderModel::whereBetween('last_date',[$start,$end])->get();
		

		
		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['request'] = $request->all();
		// dd($data);
		return view('service_reminder.report',$data);
	}

	public function report_print(Request $request){
		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['vehicles'] = VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name','id');
		if($request->get('date1')==null)
			$start = ServiceReminderModel::orderBy('last_date','ASC')->take(1)->first('last_date')->last_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = ServiceReminderModel::orderBy('last_date','DESC')->take(1)->first('last_date')->last_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d',strtotime($start));
		$end = date('Y-m-d',strtotime($end));
		
		

		if(!empty($vehicle_id))
			$data['services'] = ServiceReminderModel::where('vehicle_id',$vehicle_id)->whereBetween('last_date',[$start,$end])->get();
		else
			$data['services'] = ServiceReminderModel::whereBetween('last_date',[$start,$end])->get();

		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['request'] = $request->all();
		$data['vehicle'] = VehicleModel::find($vehicle_id);
		$data['from_date'] = Helper::getCanonicalDate($start);
		$data['to_date'] = Helper::getCanonicalDate($end);
		// dd($data);
		return view('service_reminder.print_report',$data);
	}

}
