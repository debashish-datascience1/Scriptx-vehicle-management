<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequest;
use App\Model\Expense;
use App\Model\FuelModel;
use App\Model\VehicleModel;
use App\Model\Vendor;
use App\Model\FuelType;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use Log;
use Hyvikk;
use Helper;
use Auth;
use DB;
use Illuminate\Http\Request;

ini_set('max_execution_time', 180);
class FuelController extends Controller
{
	public function index()
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$vehicle_ids = VehicleModel::pluck('id')->toArray();
		} else {
			$vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
		}
		$data['data'] = FuelModel::orderBy('id', 'desc')->whereIn('vehicle_id', $vehicle_ids)
			// ->select('id','vehicle_id','user_id','fuel_type','start_meter','qty','cost_per_unit','province','date')
			->paginate(25);
		foreach ($data['data'] as $d) {
			$trash = Transaction::where(['from_id' => $d->id, 'param_id' => 20]);
			$d->is_transaction = $trash->exists() ? true : false;
		}
		// dd($data);
		return view('fuel.index', $data);
		// return view('fuel.index-datatable');
	}

	public function getFuelList(Request $request)
	{
		// dd($request->all());
		$draw = $request->get('draw');
		$start = $request->get('start');
		$rowperpage = $request->get('length'); //Rows display per page

		$columnIndex_arr = $request->get('order');
		$columnName_arr = $request->get('columns');
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');

		$columnIndex = $columnIndex_arr[0]['column']; //Column Index
		$columnName = $columnName_arr[$columnIndex]['data']; //Column Name
		$columnSortOrder = $order_arr[0]['dir']; // ASC or DESC
		$searchValue = $search_arr['value']; //Search Value
		$columnSortOrder = 'desc';

		// Filter /Search..
		$vehicleData = VehicleModel::orderBy('id', 'ASC')->get();
		if ($searchValue != '' && Helper::string_exists(":", $searchValue)) {
			$exploded = explode(",", $searchValue);
			if (count($exploded) > 0) {
				foreach ($exploded as $k => $v) {
					// explode Individually
					$individual = explode(":", $v);
					// Log::info(json_encode($individual));
					$colName = $individual[0];
					$colVal = $individual[1];
					if ($colName == "make") { //Maker
						$vehicleData = $vehicleData->reject(function ($element) use ($colVal) {
							// Log::info($element);
							return mb_strpos($element->make, $colVal) === false;
						});
					}

					if ($colName == "model") { //model
						$vehicleData = $vehicleData->reject(function ($element) use ($colVal) {
							// Log::info($element->model);
							// Log::info($colVal);
							return mb_strpos($element->model, $colVal) === false;
						});
					}

					if ($colName == "license") { //license
						$vehicleData = $vehicleData->reject(function ($element) use ($colVal) {
							// Log::info($element);
							return mb_strpos($element->license_plate, $colVal) === false;
						});
					}

					if ($colName == "date" && strlen($colVal) > 3) { //Date
						$search = Helper::dateSearch($colVal);
						$dateSeached = FuelModel::where('date', 'LIKE', '%' . $search . '%')->pluck('id');
						// Log::info($dateSeached);
					}
					if ($colName == "fuel") { //license
						$fuelids = FuelType::where('fuel_name', 'LIKE', '%' . $colVal . '%')->pluck('id');
					}
					if ($colName == "state" && strlen($colVal) > 3) { //license
						$stateSeached = FuelModel::where('province', 'LIKE', '%' . $colVal . '%')->pluck('id');
					}
				}
			}
		}

		$ids = $vehicleData->pluck('id');
		// $dateSeached=[];
		// $fuelids=[];
		// $stateSeached=[];

		// Total records
		$totalRecords = FuelModel::select("count(*) as allcount")->count();
		$totalRecordswithFilter = FuelModel::select("count(*) as allcount")->whereIn('vehicle_id', $ids);

		if (isset($dateSeached) && count($dateSeached) > 0) { //Date
			$totalRecordswithFilter = FuelModel::select("count(*) as allcount")->whereIn('vehicle_id', $ids)->whereIn('id', $dateSeached);
		}
		if (isset($fuelids) && count($fuelids) > 0) { //Fuel IDS 
			$totalRecordswithFilter =  FuelModel::select("count(*) as allcount")->whereIn('vehicle_id', $ids)->whereIn('fuel_type', $fuelids);
		}
		if (isset($stateSeached)  && count($stateSeached) > 0) { //State 
			$totalRecordswithFilter = FuelModel::select("count(*) as allcount")->whereIn('vehicle_id', $ids)->whereIn('id', $stateSeached);
		}
		$totalRecordswithFilter = $totalRecordswithFilter->count();
		// Fetch Records
		$records = FuelModel::whereIn('vehicle_id', $ids)
			->select('fuel.*')
			->skip($start)
			->take($rowperpage)
			->orderBy($columnName, $columnSortOrder)
			->get();

		// Fuel date
		if (isset($dateSeached) && !empty($dateSeached) && count($dateSeached) > 0) { //Date
			$records = FuelModel::whereIn('vehicle_id', $ids)
				->whereIn('id', $dateSeached)
				->select('fuel.*')
				->skip($start)
				->take($rowperpage)
				->orderBy($columnName, $columnSortOrder)
				->get();
		}
		if (isset($fuelids) && !empty($fuelids) && count($fuelids) > 0) { //Fuel IDS 
			$records = FuelModel::whereIn('vehicle_id', $ids)
				->whereIn('fuel_type', $fuelids)
				->select('fuel.*')
				->skip($start)
				->take($rowperpage)
				->orderBy($columnName, $columnSortOrder)
				->get();
		}
		if (isset($stateSeached) && !empty($stateSeached) && count($stateSeached) > 0) { //State 
			$records = FuelModel::whereIn('vehicle_id', $ids)
				->whereIn('id', $stateSeached)
				->select('fuel.*')
				->skip($start)
				->take($rowperpage)
				->orderBy($columnName, $columnSortOrder)
				->get();
		}

		//'id','vehicle_id','user_id','fuel_type','start_meter','qty',
		//'cost_per_unit','province','date'

		// Log::info(json_encode($order_arr[0]));
		$data_arr = array();
		$sno = $start + 1;
		foreach ($records as $record) {
			$id = $record->id;
			$vehicle_id = $record->vehicle_id;
			$user_id = $record->user_id;
			$fuel_type = $record->fuel_type;
			$start_meter = $record->start_meter;
			$qty = $record->qty;
			$cost_per_unit = $record->cost_per_unit;
			$province = $record->province;
			$date = $record->date;
			// $province = $ids;
			// $province = empty($v) ? "abc" : $v;
			// Getting Trasaction
			$trash = Transaction::where(['from_id' => $record->id, 'param_id' => 20]);
			$record->is_transaction = $trash->exists() ? true : false;


			// prep the fields
			$checkbox = "<input type='checkbox' name='ids[]' value='$id' class='checkbox' id='chk$id' onclick='checkcheckbox();'>";

			//vehicle column
			$vehicle_colmn = "<strong>";
			$vehicle_colmn .= date('d-M-Y', strtotime($record->vehicle_data->year));
			$vehicle_colmn .= "</strong><br>";
			$vehicle_colmn .= "<a href='vehicles/$vehicle_id/edit'>";
			$vehicle_colmn .=  $record->vehicle_data->make . "-" . $record->vehicle_data->model;
			$vehicle_colmn .= "</a><br>";

			if ($record->vehicle_data->vehicle_image != null) {
				$image = $record->vehicle_data->vehicle_image;
				$vehicle_colmn .= "<a href='../uploads/$image' target='_blank' class='badge badge-danger'>";
				$vehicle_colmn .= strtoupper($record->vehicle_data->license_plate);
				$vehicle_colmn .= "</a>";
			} else {
				$vehicle_colmn .= "<a href='#' target='_blank' class='badge badge-danger'>";
				$vehicle_colmn .= strtoupper($record->vehicle_data->license_plate);
				$vehicle_colmn .= "</a>";
			}

			// Fuel
			if ($record->fuel_details != '')
				$fuelCol = $record->fuel_details->fuel_name;
			else
				$fuelCol = "<small style='color:red'>specify fuel type</small>";

			// Qty
			$qtyCol = $record->qty . " Litre";

			// start meter
			$total = $record->qty * $record->cost_per_unit;
			$startMeter_colm =  Hyvikk::get('currency') . " " . $total;
			$startMeter_colm .= "<br>";
			$startMeter_colm .= Hyvikk::get('currency') . " " . $record->cost_per_unit . "/" . Hyvikk::get('fuel_unit');

			//action button
			$action_colmn = "<div class='btn-group'><button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown'><span class='fa fa-gear'></span><span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu custom' role='menu'>";
			if ($record->is_transaction) {
				$action_colmn .= "<a class='dropdown-item' href='fuel/$record->id/edit'><span aria-hidden='true' class='fa fa-edit' style='color: #f0ad4e;'></span> Edit</a>";
				$action_colmn .= "<a class='dropdown-item' data-id='$record->id' data-toggle='modal' data-target='#myModal'><span aria-hidden='true' class='fa fa-trash' style='color: #dd4b39'></span> Delete</a>";
			}
			$action_colmn .= "</div></div>";
			//   {!! Form::open(['url' => 'admin/fuel/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
			//   {!! Form::hidden("id",$row->id) !!}
			//   {!! Form::close() !!}"
			$action = 'admin/fuel/' . $record->id;
			$token = csrf_token();
			$action_colmn .= "<form method='POST' action='$action' class='form-horizontal>
								<input name='_method' type='hidden' value='DELETE'>
								<input name='_token' type='hidden' value='$token'>
								<input name='id' type='hidden' value='$record->id'>
							  </form>";

			$data_arr[] = [
				'id' => $checkbox,
				'vehicle_id' => $vehicle_colmn,
				'date' => date("d-m-Y", strtotime($date)),
				'fuel_type' => $fuelCol,
				'qty' => $qtyCol,
				'start_meter' => $startMeter_colm,
				'province' => $province,
				'action' => $action_colmn,
				// 'user_id'=>$user_id
			];
		}

		$response = [
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordswithFilter,
			"aaData" => $data_arr
		];
		// return redirect()->json($response);
		echo json_encode($response);
		exit;
	}

	public function create()
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::whereIn_service("1")->get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
		}
		$data['vendors'] = Vendor::where('type', 'fuel')->get();
		$data['oil'] = FuelType::pluck('fuel_name', 'id');
		$data['is_gst'] = [1 => 'Yes', 2 => 'No'];
		// dd($data);
		return view('fuel.create', $data);
	}

	public function store(FuelRequest $request)
	{
		// dd($request->all());
		$start_mtr = $request->get('start_meter');
		$start_mtr = empty($start_mtr) ? 0 : $start_mtr;
		$fuel = new FuelModel();
		$fuel->vehicle_id = $request->get('vehicle_id');
		$fuel->user_id = $request->get('user_id');

		// GST Calculations
		$cost = empty($request->cost_per_unit) ? null : (float) $request->cost_per_unit;
		$qty = empty($request->qty) ? null : (float) $request->qty;
		$cgst = empty($request->cgst) ? null : (float) $request->cgst;
		$sgst = empty($request->sgst) ? null : (float) $request->sgst;
		$isgst = !empty($request->is_gst) ? $request->is_gst : null;

		$total = $cost * $qty;
		// dd($total);
		// CGST Calculation
		if (!empty($cgst)) {
			$cgstval = ($cgst / 100) * $total;
		} else {
			$cgstval = null;
		}

		// SGST Calculation
		if (!empty($sgst)) {
			$sgstval = ($sgst / 100) * $total;
		} else {
			$sgstval = null;
		}
		// dd($cgstval);
		$grandtotal = $total + $cgstval + $sgstval;
		// $abc = [$cgstval,$sgstval,$total,$grandtotal];
		// dd($abc);
		$fuel->is_gst = $isgst;
		$fuel->cgst = $cgst;
		$fuel->sgst = $sgst;
		$fuel->cgst_amt = bcdiv($cgstval, 1, 2);
		$fuel->sgst_amt = bcdiv($sgstval, 1, 2);
		$fuel->total = bcdiv($total, 1, 2);
		$fuel->grand_total = bcdiv($grandtotal, 1, 2);
		$fuel->reference = $request->get('reference');
		$fuel->province = $request->get('province');
		$fuel->note = $request->get('note');
		$fuel->qty = $request->get('qty');
		$fuel->fuel_from = $request->get('fuel_from');
		$fuel->fuel_type = $request->get('fuel_type');
		$fuel->vendor_name = $request->get('vendor_name');
		$fuel->cost_per_unit = $cost;
		$fuel->date = $request->get('date');
		$fuel->complete = $request->get("complete");
		$fuel->start_meter = $start_mtr;
		$fuel->save();



		// Accounting
		if (!empty($request->qty) && !empty($request->cost_per_unit)) {
			$account['from_id'] = $fuel->id; //Fuel id
			$account['type'] = 24; //Debit 
			$account['param_id'] = 20; //From Fuel
			$account['advance_for'] = null; //No advance given
			$account['total'] = bcdiv($grandtotal, 1, 2);


			$transid = Transaction::create($account)->id;
			$trash = ['type' => 24, 'from' => 20, 'id' => $transid];
			$transaction_id = Helper::transaction_id($trash);
			Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

			$income['transaction_id'] = $transid;
			$income['payment_method'] = 16; //Cash
			$income['date'] = $request->get('date');
			$income['amount'] = 0;
			$income['remaining'] = bcdiv($grandtotal, 1, 2);
			$income['remarks'] = null;

			IncomeExpense::create($income);
		}
		// VehicleModel::where('id', $request->vehicle_id)->update(['mileage' => $request->start_meter]);
		return redirect('admin/fuel/create');
	}
	public function report()
	{
		//dd('xxx');
		$index['vendors'] = Vendor::pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['request'] = null;

		return view('fuel.report', $index);
	}

	public function report_post(Request $request)
	{
		// dd($request->all());
		$vendor_id = $request->vendor;
		$fuel_type = $request->fuel_type;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;
		// $abc['vendor_id'] = $vendor_id;
		// $abc['fuel_type'] = $fuel_type;
		// $abc['from_date'] = $from_date;
		// $abc['to_date'] = $to_date;
		// dd($abc);
		if (empty($fuel_type) && empty($vendor_id))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->select('id', 'vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'))->groupBy('vendor_name', 'fuel_type');
		elseif (empty($vendor_id))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type)->select('id', 'vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'))->groupBy('vendor_name', 'fuel_type');
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor_id)->select('id', 'vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'))->groupBy('vendor_name', 'fuel_type');
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vendor_name' => $vendor_id, 'fuel_type' => $fuel_type])->select('id', 'vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'))->groupBy('vendor_name', 'fuel_type');

		$fuel = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
		$other = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', null)->get();
		$union = $fuel->union($other);

		foreach ($union as $fs) {
			// $sum = $fs->qty * $fs->cost_per_unit;
			$fmodel = FuelModel::where(['vendor_name' => $fs->vendor_name, 'fuel_type' => $fs->fuel_type]);

			$fuelSum = 0;
			foreach ($fmodel->get() as $anotheri) {
				$sum = $anotheri->qty * $anotheri->cost_per_unit;
				$fuelSum += $sum;
			}

			$fs->total_cost = $fuelSum;
			// $fs->total_costARR=[$fuelSum];
		}
		// $fuelSum=0;
		// foreach($index['fuel'] as $fs){
		// 	$sum = $fs->qty * $fs->cost_per_unit;
		// 	$fuelSum+=$sum;
		// }
		// $index['fuelSum']= $fuelSum;
		$index['fuel'] = $union;

		$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['result'] = '';
		$index['request'] = $request->all();
		// dd($index);
		return view('fuel.report', $index);
	}
	public function show()
	{
		dd("show method");
	}

	public function print_fuel_vendor(Request $request)
	{
		// dd($request->all());
		$vendor_id = $request->vendor;
		$fuel_type = $request->fuel_type;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

		if (empty($fuel_type) && empty($vendor_id))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->select('vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vendor_name', 'fuel_type');
		elseif (empty($vendor_id))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type)->select('vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vendor_name', 'fuel_type');
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor_id)->select('vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vendor_name', 'fuel_type');
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vendor_name' => $vendor_id, 'fuel_type' => $fuel_type])->select('vendor_name', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vendor_name', 'fuel_type');

		$fuel = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
		$other = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', null)->get();
		$index['fuel'] = $fuel->union($other);
		$index['date'] = collect(['from_date' => $from_date, 'to_date' => $to_date]);

		// dd($index);
		return view('fuel.report-print', $index);
	}


	public function edit($id)
	{

		// return $id;
		//$data['vehicles'] = VehicleModel::get();

		$index['data'] = $data = FuelModel::find($id);
		$index['vehicle_id'] = $data->vehicle_id;
		$index['vendors'] = Vendor::where('type', 'fuel')->get();
		$index['oil'] = FuelType::pluck('fuel_name', 'id');
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$index['vehicles'] = VehicleModel::whereIn_service("1")->get();
		} else {
			$index['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
		}
		$index['is_gst'] = [1 => 'Yes', 2 => 'No'];
		//$data['vehicles'] = VehicleModel::get();
		//  dd($index);
		return view('fuel.edit', $index);
	}

	public function update(FuelRequest $request)
	{
		// dd($request->all());
		$fuel = FuelModel::find($request->get("id"));
		$start_mtr = $request->get('start_meter');

		// GST Calculations
		$cost = empty($request->cost_per_unit) ? null : (float) $request->cost_per_unit;
		$qty = empty($request->qty) ? null : (float) $request->qty;
		$cgst = empty($request->cgst) ? null : (float) $request->cgst;
		$sgst = empty($request->sgst) ? null : (float) $request->sgst;
		$isgst = !empty($request->is_gst) ? $request->is_gst : null;
		// dd($isgst);
		$total = $cost * $qty;
		// dd($total);
		// CGST Calculation
		if (!empty($cgst)) {
			$cgstval = ($cgst / 100) * $total;
		} else {
			$cgstval = null;
		}

		// SGST Calculation
		if (!empty($sgst)) {
			$sgstval = ($sgst / 100) * $total;
		} else {
			$sgstval = null;
		}
		// dd($cgstval);
		$grandtotal = $total + $cgstval + $sgstval;
		// $abc = [$cgstval,$sgstval,$total,$grandtotal];
		// dd($abc);
		$fuel->is_gst = $isgst;
		$fuel->cgst = $cgst;
		$fuel->sgst = $sgst;
		$fuel->cgst_amt = bcdiv($cgstval, 1, 2);
		$fuel->sgst_amt = bcdiv($sgstval, 1, 2);
		$fuel->total = bcdiv($total, 1, 2);
		$fuel->grand_total = bcdiv($grandtotal, 1, 2);

		$fuel->vehicle_id = $request->get('vehicle_id');
		$fuel->start_meter = empty($start_mtr) ? 0 : $start_mtr;
		$fuel->reference = $request->get('reference');
		$fuel->province = $request->get('province');
		$fuel->note = $request->get('note');
		$fuel->qty = $request->get('qty');
		$fuel->fuel_type = $request->get('fuel_type');
		$fuel->fuel_from = $request->get('fuel_from');
		$fuel->vendor_name = $request->get('vendor_name');
		$fuel->cost_per_unit = $request->get('cost_per_unit');
		$fuel->date = $request->get('date');
		$fuel->complete = $request->get("complete");
		// if ($fuel->end_meter != 0) {
		// 	$fuel->consumption = ($fuel->end_meter - $request->get('start_meter')) / $request->get('qty');
		// }
		// dd($fuel);
		$fuel->save();
		if (!empty($request->qty) && !empty($request->cost_per_unit)) {
			Transaction::where(['param_id' => 20, 'from_id' => $request->id])->update(['total' => $grandtotal]); //param_id = 20 fuel

			$transaction_id = DB::table('transactions')
				->select('id')
				->where('from_id', '=', $request->id)
				->first()->id;

			IncomeExpense::where('transaction_id', $transaction_id)->update(['remaining' => $grandtotal]);
		}
		// VehicleModel::where('id', $request->vehicle_id)->update(['mileage' => $request->start_meter]);
		return redirect()->route('fuel.index');
	}

	public function destroy(Request $request)
	{

		$form_id = DB::table('transactions')
			->select('id')
			->where('from_id', '=', $request->get('id'))
			->where('param_id', '=', 20)
			->where('type', '=', 24)
			->first()->id;

		IncomeExpense::where('transaction_id', $form_id)->delete();
		Transaction::where('param_id', 20)->where('type', 24)->where('from_id', $request->get('id'))->delete();
		FuelModel::where('id', $request->get('id'))->delete();
		return redirect()->route('fuel.index');
	}

	public function bulk_delete(Request $request)
	{
		dd($request->ids);

		$from_id = Transaction::select('id')->where('param_id', 20)->where('type', 24)->whereIn('from_id', $request->get('ids'))->get();
		IncomeExpense::whereIn('transaction_id', $from_id)->delete();
		Transaction::where('param_id', 20)->where('type', 24)->whereIn('from_id', $request->get('ids'))->delete();
		FuelModel::whereIn('id', $request->ids)->delete();
		// Expense::whereIn('exp_id', $request->ids)->where('expense_type', 8)->delete();
		return back();
	}

	public function report_vehicle()
	{
		$fuelVehicles = FuelModel::select('vehicle_id')->groupBy('vehicle_id')->pluck('vehicle_id');
		// dd($fuelVehicles);
		$index['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->whereIn('id', $fuelVehicles)->orderBy("name", "ASC")->pluck("name", "id");
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['request'] = null;
		// dd($index);
		return view('fuel.report-vehicle', $index);
	}

	public function report_vehiclepost(Request $request)
	{
		// dd($request->toArray());
		$vehicle = $request->vehicle;
		$fuel_type = $request->fuel_type;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

		if (empty($vehicle) && empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');
		elseif (empty($vehicle))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vehicle_id', $vehicle)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vehicle_id', $vehicle)->where('fuel_type', $fuel_type)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');

		// dd($fuelModel->get());

		$index['fuelv'] = $fuelModel->orderBy('vehicle_id', 'ASC')->get();
		//	$index['fuelv'] = $fuelModel->orderBy('vehicle_id','ASC')->get();
		foreach ($index['fuelv'] as $sum) {
			$summation[] = $sum->total_cost;
			$summationqty[] = $sum->qty;
		}

		$fuelVehicles = FuelModel::select('vehicle_id')->groupBy('vehicle_id')->pluck('vehicle_id');
		$summation = !empty($summation) ? $summation : [0];
		$index['grandtotal_cost'] = array_sum($summation);
		$index['grandtotal_qty'] = array_sum($summationqty);
		$index['request'] = $request->all();
		$index['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->whereIn('id', $fuelVehicles)->orderBy("name", "ASC")->pluck("name", "id");
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['result'] = "";
		// dd($index);
		return view('fuel.report-vehicle', $index);
	}
	public function report_vehicleprint(Request $request)
	{
		// dd($request->toArray());
		$vehicle = $request->vehicle;
		$fuel_type = $request->fuel_type;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

		if (empty($vehicle) && empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');
		elseif (empty($vehicle))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vehicle_id', $vehicle)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vehicle_id', $vehicle)->where('fuel_type', $fuel_type)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'), DB::raw('SUM(consumption) as consumption'), DB::raw('SUM(qty) * AVG(cost_per_unit) as total_cost'))->groupBy('vehicle_id', 'fuel_type');

		// dd($fuelModel->get());
		foreach ($fuelModel->get() as $sum) {
			$summation[] = $sum->total_cost;
			$summationqty[] = $sum->qty;
		}

		$summation = !empty($summation) ? $summation : [0];
		$index['grandtotal_cost'] = array_sum($summation);
		$index['grandtotal_qty'] = array_sum($summationqty);
		$index['fuelv'] = $fuelModel->orderBy('vehicle_id', 'ASC')->get();
		$index['date'] = collect(['from_date' => $from_date, 'to_date' => $to_date]);
		return view('fuel.report-vehicle-print', $index);
	}
	public function view_fuel_details($arr)
	{

		$arr = explode(",", $arr);
		//dd($arr);
		if (count($arr) > 0 && $arr[0] != '' && $arr[1] != '') {

			$vendor_id = $arr[0];
			$fuel_type = $arr[1];

			$from_date = $arr[2];
			$to_date = $arr[3];
			$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
			$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vendor_name' => $vendor_id, 'fuel_type' => $fuel_type]);

			$fuel = $fuelModel->get();
			// $index['fuel']= $fuelModel->get();

			$fuel = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
			$other = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', null)->get();
			$index['fuel'] = $fuel->union($other);

			$fuelSum = 0;
			foreach ($index['fuel'] as $fs) {
				$sum = $fs->qty * $fs->cost_per_unit;
				$fuelSum += $sum;
			}
			$index['fuelSum'] = $fuelSum;

			$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
			$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
			$index['result'] = '';
			$index['vendor_id'] = $vendor_id;
			$index['vendor_name'] = DB::table('vendors')->select('name')->where('id', '=', $vendor_id)->first()->name;
			$index['fuel_name'] = DB::table('fuel_type')->select('fuel_name')->where('id', '=', $fuel_type)->first()->fuel_name;
			$index['fuel_type'] = $fuel_type;
			$index['from_date'] = $from_date;
			$index['to_date'] = $to_date;

			return view('fuel.view_fuel_details', $index);
		} else {
			dd($arr);
		}
	}

	public function print_fuel_Details(Request $request)
	{

		$vendor_id = $request->vendor_id;
		$fuel_type = $request->fuel_type;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$vendor_name = $request->vendor_name;
		$fuel_name = $request->fuel_name;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

		$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vendor_name' => $vendor_id, 'fuel_type' => $fuel_type]);

		$fuel = $fuelModel->get();
		// $index['fuel']= $fuelModel->get();

		$fuel = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
		$other = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', null)->get();
		$index['fuel'] = $fuel->union($other);

		$fuelSum = 0;
		foreach ($index['fuel'] as $fs) {
			$sum = $fs->qty * $fs->cost_per_unit;
			$fuelSum += $sum;
		}
		$index['fuelSum'] = $fuelSum;

		$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['result'] = '';
		$index['vendor_name'] = $vendor_name;
		$index['fuel_name'] = $fuel_name;

		return view('fuel.fuelDetails-print', $index);
	}
	public function view_vehicle_fuel_details($arr)
	{

		// dd($arr);
		$arr = explode(",", $arr);
		if (count($arr) > 0 && $arr[0] != '' && $arr[1] != '') {

			$vehicle_id = $arr[0];
			$fuel_type = $arr[1];

			$from_date = $arr[2];
			$to_date = $arr[3];
			$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
			$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vehicle_id' => $vehicle_id, 'fuel_type' => $fuel_type]);

			$fuel = $fuelModel->get();
			// $index['fuel']= $fuelModel->get();

			$fuel = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
			$other = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', null)->get();
			$index['fuel'] = $fuel->union($other);

			$fuelSum = 0;
			foreach ($index['fuel'] as $fs) {
				$sum = $fs->qty * $fs->cost_per_unit;
				$fuelSum += $sum;
			}
			$index['fuelSum'] = $fuelSum;

			$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
			$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
			$index['result'] = '';
			$index['vehicle_id'] = $vehicle_id;
			$index['vehicle_name'] = VehicleModel::select(DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('id', $vehicle_id)->first()->name;
			$index['fuel_name'] = DB::table('fuel_type')->select('fuel_name')->where('id', '=', $fuel_type)->first()->fuel_name;
			$index['fuel_type'] = $fuel_type;
			$index['from_date'] = $from_date;
			$index['to_date'] = $to_date;
			// dd($index);
			return view('fuel.view_vehicle_fuel_details', $index);
		} else {
			dd($arr);
		}
	}

	public function print_vehicle_fuel_Details(Request $request)
	{
		// dd($request->all());
		$vehicle_id = $request->vehicle_id;
		$fuel_type = $request->fuel_type;
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$vehicle_name = $request->vehicle_name;
		$fuel_name = $request->fuel_name;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

		$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vehicle_id' => $vehicle_id, 'fuel_type' => $fuel_type]);

		$fuel = $fuelModel->get();
		// $index['fuel']= $fuelModel->get();

		$index['fuel'] = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
		// $other= $fuelModel->where('fuel_type','!=',null)->where('vendor_name',null)->get();
		// $index['fuel'] = $fuel->union($other);

		$fuelSum = 0;
		foreach ($index['fuel'] as $fs) {
			$sum = $fs->qty * $fs->cost_per_unit;
			$fuelSum += $sum;
		}
		$index['fuelSum'] = $fuelSum;

		$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['result'] = '';
		$index['vehicle_name'] = $vehicle_name;
		$index['fuel_name'] = $fuel_name;

		return view('fuel.fuelVendorDetails-print', $index);
	}

	public function fuel_gstcalculate(Request $request)
	{
		// dd($request->all());
		// return response()->json($request->all());
		$cost = empty($request->cost) ? 0 : (float) $request->cost;
		$qty = empty($request->qty) ? 0 : (float) $request->qty;
		$cgst = empty($request->cgst) ? 0 : (float) $request->cgst;
		$sgst = empty($request->sgst) ? 0 : (float) $request->sgst;

		$total = $cost * $qty;

		// CGST Calculation
		if (!empty($cgst)) {
			$cgstval = ($cgst / 100) * $total;
		} else {
			$cgstval = null;
		}

		// SGST Calculation
		if (!empty($sgst)) {
			$sgstval = ($sgst / 100) * $total;
		} else {
			$sgstval = null;
		}

		$grandtotal = $total + $cgstval + $sgstval;
		$response['total'] = bcdiv($total, 1, 2);
		$response['cgstval'] = bcdiv($cgstval, 1, 2);
		$response['sgstval'] = bcdiv($sgstval, 1, 2);
		$response['grandtotal'] = bcdiv($grandtotal, 1, 2);
		return response()->json($response);
	}

	public function view_event($id)
	{
		// dd($id);
		$data['row'] = FuelModel::find($id);
		// dd($data);
		return view('fuel.view_event', $data);
	}
}
