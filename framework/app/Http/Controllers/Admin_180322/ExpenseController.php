<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpRequest;
use App\Model\ExpCats;
use App\Model\Expense;
use App\Model\ServiceItemsModel;
use App\Model\VehicleModel;
use App\Model\Vendor;
use Auth;
use DB;
use Illuminate\Http\Request;

class ExpenseController extends Controller {
	public function index(Request $request) {
		$user = Auth::user();
		if ($user->group_id == null || $user->user_type == "S") {
			$data['vehicels'] = VehicleModel::whereIn_service(1)->get();

		} else {
			$data['vehicels'] = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->get();
		}
		$vehicle_ids = $data['vehicels']->pluck('id')->toArray();
		$data['types'] = ExpCats::get();
		$data['service_items'] = ServiceItemsModel::get();

		$data['total'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->sum('amount');
		$data['today'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->get();
		$data['vendors'] = Vendor::get();
		$data['date1'] = null;
		$data['date2'] = null;
		return view("expense.index", $data);
	}

	public function store(ExpRequest $request) {

		$result = explode('_', $request->get("expense_type"));
		Expense::create([
			"vehicle_id" => $request->get("vehicle_id"),
			"amount" => $request->get("revenue"),
			"user_id" => Auth::id(),
			"date" => $request->get('date'),
			"comment" => $request->get('comment'),
			"expense_type" => $result[1],
			"type" => $result[0],
			"vendor_id" => $request->get('vendor_id'),
		]);

		return redirect()->route("expense.index");
	}

	public function destroy(Request $request) {
		Expense::find($request->get('id'))->delete();
		$user = Auth::user();
		if ($user->group_id == null || $user->user_type == "S") {
			$vehicle_ids = VehicleModel::whereIn_service(1)->pluck('id')->toArray();
		} else {
			$vehicle_ids = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->pluck('id')->toArray();
		}
		$data['today'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->get();
		$data['total'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->sum('amount');
		return view("expense.ajax_expense", $data);
		// return redirect()->route('expense.index');
	}

	public function expense_records(Request $request) {
		$user = Auth::user();
		if ($user->group_id == null || $user->user_type == "S") {
			$data['vehicels'] = VehicleModel::whereIn_service(1)->get();

		} else {
			$data['vehicels'] = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->get();
		}
		$vehicle_ids = $data['vehicels']->pluck('id')->toArray();
		$data['types'] = ExpCats::get();
		$data['today'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$request->get('date1'), $request->get('date2')])->get();
		$data['service_items'] = ServiceItemsModel::get();
		$data['total'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->sum('amount');
		$data['vendors'] = Vendor::get();
		$data['date1'] = $request->date1;
		$data['date2'] = $request->date2;
		return view("expense.index", $data);
	}

	public function bulk_delete(Request $request) {
		Expense::whereIn('id', $request->ids)->delete();
		return redirect('admin/expense');
	}

}