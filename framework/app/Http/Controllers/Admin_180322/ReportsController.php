<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdvanceDriver;
use App\Model\Bookings;
use App\Model\ExpCats;
use App\Model\Expense;
use App\Model\PartsModel;
use App\Model\FuelModel;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\ServiceItemsModel;
use App\Model\User;
use App\Model\Params;
use App\Model\VehicleModel;
use App\Model\WorkOrders;
use App\Model\Vendor;
use App\Model\Transaction;
use App\Model\BankAccount;
use App\Model\BankTransaction;
use App\Model\BulkPayment;
use App\Model\DailyAdvance;
use App\Model\FuelType;
use App\Model\Payroll;
use App\Model\DriverVehicleModel;
use App\Model\EmiModel;
use App\Model\IncomeExpense;
use App\Model\Leave;
use App\Model\OtherAdjust;
use App\Model\OtherAdvance;
use App\Model\PartsInvoice;
use App\Model\PartsUsedModel;
use App\Model\PurchaseInfo;
use App\Model\ServiceReminderModel;
use App\Model\VehicleDocs;
use Auth;
use DB;
use Helper;
use Hash;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

	public function expense()
	{
		$years = collect(DB::select("select distinct year(date) as years from expense where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle['id'];
		}

		$data['vehicle_id'] = "";
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['years'] = $y;

		$data['expense'] = Expense::whereIn('vehicle_id', $vehicle_ids)->whereYear("date", date("Y"))->whereMonth("date", date('m'))->get();
		return view('reports.expense', $data);
	}

	public function expense_post(Request $request)
	{

		$years = collect(DB::select("select distinct year(date) as years from expense where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle['id'];
		}

		$data['vehicle_id'] = $request->vehicle_id;
		$data['year_select'] = $request->year;
		$data['month_select'] = $request->month;
		$data['years'] = $y;

		$records = Expense::whereIn('vehicle_id', $vehicle_ids)->whereYear("date", $request->year)->whereMonth("date", $request->month);
		if ($request->vehicle_id != null) {
			$data['expense'] = $records->where('vehicle_id', $request->vehicle_id)->get();
		} else {
			$data['expense'] = $records->get();
		}
		return view('reports.expense', $data);
	}

	public function expense_print(Request $request)
	{
		$data['vehicle_id'] = $request->vehicle_id;
		$data['year_select'] = $request->year;
		$data['month_select'] = $request->month;
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {

			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle['id'];
		}
		$records = Expense::whereIn('vehicle_id', $vehicle_ids)->whereYear("date", $request->year)->whereMonth("date", $request->month);
		if ($request->vehicle_id != null) {
			$data['expense'] = $records->where('vehicle_id', $request->vehicle_id)->get();
		} else {
			$data['expense'] = $records->get();
		}
		return view('reports.print_expense', $data);
	}

	public function income()
	{

		$years = collect(DB::select("select distinct year(date) as years from income where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle['id'];
		}

		$data['vehicle_id'] = "";
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['years'] = $y;

		$data['income'] = IncomeModel::whereIn('vehicle_id', $vehicle_ids)->whereYear("date", date("Y"))->whereMonth("date", date('m'))->get();
		return view('reports.income', $data);
	}

	public function income_post(Request $request)
	{
		$years = collect(DB::select("select distinct year(date) as years from income where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle['id'];
		}

		$data['vehicle_id'] = $request->vehicle_id;
		$data['year_select'] = $request->year;
		$data['month_select'] = $request->month;
		$data['years'] = $y;

		$records = IncomeModel::whereYear("date", $request->year)->whereMonth("date", $request->month);
		if ($request->vehicle_id != null) {
			$data['income'] = $records->where('vehicle_id', $request->vehicle_id)->get();
		} else {
			$data['income'] = $records->whereIn('vehicle_id', $vehicle_ids)->get();
		}
		return view('reports.income', $data);
	}

	public function income_print(Request $request)
	{
		$data['vehicle_id'] = $request->vehicle_id;
		$data['year_select'] = $request->year;
		$data['month_select'] = $request->month;
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle['id'];
		}

		$records = IncomeModel::whereYear("date", $request->year)->whereMonth("date", $request->month);
		if ($request->vehicle_id != null) {
			$data['income'] = $records->where('vehicle_id', $request->vehicle_id)->get();
		} else {
			$data['income'] = $records->whereIn('vehicle_id', $vehicle_ids)->get();
		}
		return view('reports.print_income', $data);
	}

	public function monthly()
	{

		$years = DB::select(DB::raw("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc"));
		$y = array();
		$c = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {

			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}

		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['vehicle_select'] = null;
		$data['years'] = $y;
		$data['income'] = IncomeModel::select(DB::raw("SUM(amount) as income"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereIn('vehicle_id', $vehicle_ids)->get();
		$data['expenses'] = Expense::select(DB::raw("SUM(amount) as expense"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereIn('vehicle_id', $vehicle_ids)->get();
		$data['expense_by_cat'] = Expense::select("type", "expense_type", DB::raw("sum(amount) as expense"))->whereYear('date', date('Y'))->whereMonth('date', date('n'))->groupBy(['expense_type', 'type'])->whereIn('vehicle_id', $vehicle_ids)->get();

		$data['income_by_cat'] = IncomeModel::select("income_cat", DB::raw("sum(amount) as amount"))->whereYear('date', date('Y'))->whereMonth('date', date('n'))->groupBy(['income_cat'])->whereIn('vehicle_id', $vehicle_ids)->get();

		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}

		$kk = ExpCats::get();

		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();

		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;
		$data['result'] = "";

		return view("reports.monthly", $data);
	}

	public function delinquent()
	{
		$years = collect(DB::select("select distinct year(date) as years from income where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get()->toArray();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get()->toArray();
		}

		$data['vehicle_id'] = "";
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['years'] = $y;

		return view("reports.delinquent", $data);
	}
	//vendor payment
	// 	public function vendorPayment()
	// 	{
	// 		// $customers=DB::table('bookings')->join('users','bookings.customer_id','=','users.id')->select('users.name as customer_name','users.id as from')->groupBy('users.name')->get;
	// 		// $customerArray = array();
	// 		// foreach($customers as $cust)
	// 		// {
	// 		// 	$customerArray[$cust->from] = $cust->customer_name;
	// 		// }
	// 		$index['from'] = Vendor::pluck('name','id');
	//         $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
	//         $index['request'] = null;
	//         //dd($index);
	//         return view('reports.vendorPayment',$index);
	// 	}

	// 	public function vendorPayment_post(Request $request){
	// 	   $from = $request->get('from');
	// 	   $payment_type = $request->get('payment_type');
	// 	   $from_date = $request->get('from_date');
	// 	   $to_date = $request->get('to_date'); 

	// 	   $from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
	// 	   $to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;

	// 	   $fromchk = empty($from) ? Vendor::pluck('id')->toArray() : $from;
	// 	   $from=Vendor::pluck('name','id');
	// 		if(!empty($payment_type)){
	// 			$transaction = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('type',$payment_type)->get();
	// 		}else{
	// 			$transaction = Transaction::whereBetween('created_at',[$from_date,$to_date])->get();
	// 		}
	// 		//dd($transaction);
	// 		foreach($transaction as $t){
	// 			//dd($t->param_id);
	// 			// if($t->param_id==20)
	// 			// {
	// 			// 	dd($t);
	// 			// }

	// 			if($t->param_id==20){
	// 				$shalom = FuelModel::where('id',$t->from_id);
	// 				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_name : null ;			
	// 			}
	// 			elseif($t->param_id==26){
	// 				$shalom = PartsInvoice::where('id',$t->from_id);
	// 				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null ;			
	// 			}
	// 			elseif($t->param_id==28){
	// 				$shalom = WorkOrders::where('id',$t->from_id);
	// 				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null ;			
	// 			}else{
	// 				$t->org_id =null;
	// 			}

	// 		}
	// 		//dd($fromchk);
	// 		if(is_array($fromchk) && count($fromchk)>0)
	// 		{	//array
	// 			//dd($fromchk);
	// 			$filtered = $transaction->where('org_id','!=',null)->whereIn('org_id',$fromchk);
	// 		}
	// 		elseif(!empty($fromchk)) //number
	// 		{
	// 			$filtered = $transaction->where('org_id','!=',null)->where('org_id',$fromchk);
	// 		}
	// 		else 
	// 		{
	// 			$filtered = $transaction->where('org_id','!=',null);
	// 		}
	// 	//dd($filtered);
	// 	// dd($filtered->first());
	// 	   $index['transaction'] = $filtered->sortByDesc('id')->values()->all();
	// 	//    dd($index['transaction']->first());
	// 	   $index['from'] = $from;
	// 	   $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
	// 	   $index['result'] = "";
	// 	   $index['sumoftotal']=$filtered->sum('total');
	// 	   $index['request'] = $request->all();
	// 	  // dd($index);
	// 	   return view('reports.vendorPayment',$index);

	//    }
	public function vendorPayment()
	{
		$index['vendors'] = Vendor::pluck('name', 'id');
		$index['heads'] = Params::whereIn('id', [20, 26, 28])->pluck('label', 'id');
		$index['request'] = null;
		//dd($index);
		return view('reports.vendorPayment', $index);
	}

	public function vendorPayment_post(Request $request)
	{
		//    dd($request->all());
		$vendor_id = $request->vendor_id;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;


		//    if(!empty($head)){
		// 		$transactions = Transaction::where('param_id',$head)->get();
		//    }else{
		// 	}
		$transactions = Transaction::whereIn('param_id', [20, 26, 28])->where('type', '24')->get();
		// dd($transactions);
		foreach ($transactions as $t) {
			if ($t->param_id == 20) {
				$shalom = FuelModel::where('id', $t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_name : null;
				$t->date = $shalom->exists() ? $shalom->first()->date : null;
			} elseif ($t->param_id == 26) {
				$shalom = PartsInvoice::where('id', $t->from_id);
				// if($t->id==975) dd($shalom->first()->singledetails);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null;
				$t->date = $shalom->exists() ? (!empty($shalom->first()->singledetails) ? $shalom->first()->singledetails->date_of_purchase : null) : null;
			} elseif ($t->param_id == 28) {
				$shalom = WorkOrders::where('id', $t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null;
				$t->date = $shalom->exists() ? $shalom->first()->required_by : null;
			} else {
				$t->org_id = null;
			}
			$t->is_bulk = null;
		}
		// dd($transactions->where('id',975));
		$transactions = $transactions->where('org_id', $vendor_id)->where('date', '!=', null);
		// dd($transactions->reverse()->take(15)->toArray());
		// dd($transactions->reverse()->take(15)->toArray());

		//Getting Bulk Data

		// $abc = [
		// 	"id" => 16,
		// 	"transaction_id" => "EX-FU-16",
		// 	"type" => 24,
		// 	"from_id" => 330,
		// 	"param_id" => 20,
		// 	"bank_id" => null,
		// 	"advance_for" => null,
		// 	"from_transaction" => null,
		// 	"total" => "10116.89",
		// 	"is_completed" => null,
		// 	"created_at" => "2021-05-06 11:04:44",
		// 	"updated_at" => "2021-05-06 11:04:45",
		// 	"deleted_at" => null,
		// 	"org_id" => "3",
		// 	"date" => "2021-05-04"
		// ];
		$bulkPayment = BulkPayment::get();
		foreach ($bulkPayment as $bp) {
			$parameter_id = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->param_id;
			if (in_array($parameter_id, [20, 26, 28])) {
				$ref_no = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->getRefNo->ref_no;

				//Vendor Check for each param_id

				if ($t->param_id == 20 && !empty($bp->single_bulk_list) && !empty($bp->single_bulk_list->transaction) && !empty($bp->single_bulk_list->transaction->fuel)) {
					$check_vendorid = $bp->single_bulk_list->transaction->fuel->vendor_name;
				} elseif ($t->param_id == 26 && !empty($bp->single_bulk_list) && !empty($bp->single_bulk_list->transaction) && !empty($bp->single_bulk_list->transaction->parts)) {
					$check_vendorid = $bp->single_bulk_list->transaction->parts->vendor_id;
				} elseif ($t->param_id == 28 && !empty($bp->single_bulk_list) && !empty($bp->single_bulk_list->transaction) && !empty($bp->single_bulk_list->transaction->workorders)) {
					$check_vendorid = $bp->single_bulk_list->transaction->workorders->vendor_id;
				} else {
					$check_vendorid = null;
				}

				if ($check_vendorid == $vendor_id) { //ensuring searched vendor data inserted
					$prep = [
						"id" => $bp->id,
						"transaction_id" => $ref_no,
						"type" => 24, // debit
						"from_id" => $bp->id,
						"param_id" => $parameter_id,
						"bank_id" => $bp->bank_id,
						"advance_for" => null,
						"from_transaction" => null,
						"total" => $bp->amount,
						"is_completed" => null,
						"created_at" => date("Y-m-d H:i:s", strtotime($bp->created_at)),
						"updated_at" => date("Y-m-d H:i:s", strtotime($bp->updated_at)),
						"deleted_at" => null,
						"org_id" => $bp->cv_id,
						"date" => $bp->date,
						"is_bulk" => 1,
						// 'bulk_data' => $bp,
					];
					$baked = Helper::toCollection($prep);
					$transactions->push($baked);
				}
			}
		}

		$transactions = $transactions->where('org_id', $vendor_id)->where('date', '!=', null)->sortBy('date')->values();
		$vndr_balance = Vendor::find($vendor_id)->opening_balance;

		// dd($transactions->first(),$transactions->reverse()->first());
		//finding out dates
		// dd($transactions);
		if (count($transactions)) {
			$first_date = count($transactions) ? $transactions->first()->date : null;
			// dd($transaction_ids);
			if (empty($from_date))
				$from_date  = $first_date;
			if (empty($to_date))
				$to_date  = $transactions->reverse()->first()->date;

			// dd($from_date,$to_date);

			$prev_transactions = $transactions->where('date', '<', $from_date)->values();
			$curr_transactions = $transactions->whereBetween('date', [$from_date, $to_date])->values();

			// dd($from_date,$to_date,$prev_transactions->reverse()->first(),$curr_transactions);

			//Opening Balance
			$cr_total = $prev_transactions->where('type', 23)->sum('total');
			$bulk_total = $prev_transactions->where('is_bulk', 1)->sum('total');
			$dr_total = $prev_transactions->where('type', 24)->where('is_bulk', '!=', 1)->sum('total');
			// dd($cr_total,$dr_total,$vndr_balance,$prev_transactions);
			// $bulkPayment = BulkPayment::whereBetween('date',[$from_date,$to_date])->where('cv_id',$vendor_id)->get();
			$opening_balance = $op = bcdiv((($dr_total + $vndr_balance) - ($cr_total + $bulk_total)), 1, 2);
			// dd($opening_balance);
			foreach ($curr_transactions as $bp) {
				if ($bp->is_bulk == 1)
					$bp->new_total = $op - $bp->total;
				else
					$bp->new_total = $op + $bp->total;

				$op = $bp->new_total;
			}
		} else {
			$opening_balance = $vndr_balance;
			$curr_transactions = $transactions;
		}


		// dd($bulk);
		$index['opening_balance'] = $opening_balance;
		// dd($incomeExpenses);
		$index['transactions'] = $curr_transactions;
		$index['vendors'] = Vendor::pluck('name', 'id');
		$index['result'] = "";
		$index['request'] = $request->all();
		//   dd($index);
		return view('reports.vendorPayment', $index);
	}


	public function vendor_payment(Request $request)
	{
		//    dd($request->all());
		$vendor_id = $request->vendor_id;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;


		//    if(!empty($head)){
		// 		$transactions = Transaction::where('param_id',$head)->get();
		//    }else{
		// 	}
		$transactions = Transaction::whereIn('param_id', [20, 26, 28])->where('type', '24')->get();
		// dd($transactions);
		foreach ($transactions as $t) {
			if ($t->param_id == 20) {
				$shalom = FuelModel::where('id', $t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_name : null;
				$t->date = $shalom->exists() ? $shalom->first()->date : null;
			} elseif ($t->param_id == 26) {
				$shalom = PartsInvoice::where('id', $t->from_id);
				// if($t->id==975) dd($shalom->first()->singledetails);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null;
				$t->date = $shalom->exists() ? (!empty($shalom->first()->singledetails) ? $shalom->first()->singledetails->date_of_purchase : null) : null;
			} elseif ($t->param_id == 28) {
				$shalom = WorkOrders::where('id', $t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null;
				$t->date = $shalom->exists() ? $shalom->first()->required_by : null;
			} else {
				$t->org_id = null;
			}
			$t->is_bulk = null;
		}
		// dd($transactions->where('id',975));
		$transactions = $transactions->where('org_id', $vendor_id)->where('date', '!=', null);
		// dd($transactions->reverse()->take(15)->toArray());
		// dd($transactions->reverse()->take(15)->toArray());

		//Getting Bulk Data

		// $abc = [
		// 	"id" => 16,
		// 	"transaction_id" => "EX-FU-16",
		// 	"type" => 24,
		// 	"from_id" => 330,
		// 	"param_id" => 20,
		// 	"bank_id" => null,
		// 	"advance_for" => null,
		// 	"from_transaction" => null,
		// 	"total" => "10116.89",
		// 	"is_completed" => null,
		// 	"created_at" => "2021-05-06 11:04:44",
		// 	"updated_at" => "2021-05-06 11:04:45",
		// 	"deleted_at" => null,
		// 	"org_id" => "3",
		// 	"date" => "2021-05-04"
		// ];
		$bulkPayment = BulkPayment::get();
		foreach ($bulkPayment as $bp) {
			$parameter_id = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->param_id;
			$ref_no = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->getRefNo->ref_no;

			if (in_array($parameter_id, [20, 26, 28])) {
				$prep = [
					"id" => $bp->id,
					"transaction_id" => $ref_no,
					"type" => 24, // debit
					"from_id" => $bp->id,
					"param_id" => $parameter_id,
					"bank_id" => $bp->bank_id,
					"advance_for" => null,
					"from_transaction" => null,
					"total" => $bp->amount,
					"is_completed" => null,
					"created_at" => date("Y-m-d H:i:s", strtotime($bp->created_at)),
					"updated_at" => date("Y-m-d H:i:s", strtotime($bp->updated_at)),
					"deleted_at" => null,
					"org_id" => $bp->cv_id,
					"date" => $bp->date,
					"is_bulk" => 1,
					// 'bulk_data' => $bp,
				];
				$baked = Helper::toCollection($prep);
				$transactions->push($baked);
			}
		}

		$transactions = $transactions->where('org_id', $vendor_id)->where('date', '!=', null)->sortBy('date')->values();
		$vndr_balance = Vendor::find($vendor_id)->opening_balance;

		// dd($transactions->first(),$transactions->reverse()->first());
		//finding out dates
		// dd($transactions);
		if (count($transactions)) {
			$first_date = count($transactions) ? $transactions->first()->date : null;
			// dd($transaction_ids);
			if (empty($from_date))
				$from_date  = $first_date;
			if (empty($to_date))
				$to_date  = $transactions->reverse()->first()->date;

			// dd($from_date,$to_date);

			$prev_transactions = $transactions->where('date', '<', $from_date)->values();
			$curr_transactions = $transactions->whereBetween('date', [$from_date, $to_date])->values();

			// dd($from_date,$to_date,$prev_transactions->reverse()->first(),$curr_transactions);

			//Opening Balance
			$cr_total = $prev_transactions->where('type', 23)->sum('total');
			$bulk_total = $prev_transactions->where('is_bulk', 1)->sum('total');
			$dr_total = $prev_transactions->where('type', 24)->where('is_bulk', '!=', 1)->sum('total');
			// dd($cr_total,$dr_total,$vndr_balance,$prev_transactions);
			// $bulkPayment = BulkPayment::whereBetween('date',[$from_date,$to_date])->where('cv_id',$vendor_id)->get();
			$opening_balance = $op = bcdiv((($dr_total + $vndr_balance) - ($cr_total + $bulk_total)), 1, 2);
			// dd($opening_balance);
			foreach ($curr_transactions as $bp) {
				if ($bp->is_bulk == 1)
					$bp->new_total = $op - $bp->total;
				else
					$bp->new_total = $op + $bp->total;

				$op = $bp->new_total;
			}
		} else {
			$opening_balance = $vndr_balance;
			$curr_transactions = $transactions;
		}


		// dd($bulk);
		$index['opening_balance'] = $opening_balance;
		// dd($incomeExpenses);
		$index['transactions'] = $curr_transactions;


		$index['vendor_data'] = Vendor::where("id", $vendor_id)->first();
		$index['date'] = date('Y-m-d H:i:s');
		$index['result'] = "";
		$index['request'] = $request->all();
		$index['from_date'] = $from_date;
		$index['to_date'] = $to_date;
		// dd($index);
		return view('reports.vendorPaymentPrint', $index);
	}

	public function customerPayment()
	{
		$customers = DB::table('bookings')->join('users', 'bookings.customer_id', '=', 'users.id')->select('users.name as customer_name', 'users.id as customer_id')->groupBy('bookings.customer_id')->pluck('customer_name', 'customer_id');

		$index['customers'] = $customers;
		$index['request'] = null;
		//dd($index);
		return view('reports.customerPayment', $index);
	}
	public function customerPayment_post(Request $request)
	{
		$customer_id = $request->customer_id;
		$from_date = $request->from_date;
		$to_date = $request->to_date;

		$transactions = Transaction::where(['param_id' => '18', 'type' => '23'])->where(function ($q) {
			$q->where('advance_for', '!=', '21')
				->orWhereRaw('transactions.advance_for IS NULL');
		})->get();
		// dd($transactions);
		foreach ($transactions as $t) {
			$shalom = Bookings::where('id', $t->from_id);
			$t->org_id = $shalom->exists() ? $shalom->first()->customer_id : null;
			$t->date = $shalom->exists() ? $shalom->first()->pickup : null;
			$t->is_bulk = null;
		}

		$transactions = $transactions->where('org_id', $customer_id)->where('date', '!=', null);
		// dd($transactions);
		$bulkPayment = BulkPayment::get();
		foreach ($bulkPayment as $bp) {
			$parameter_id = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->param_id;
			// dd($parameter_id);
			if ($parameter_id == 18) {
				//Vendor Check for each param_id
				$check_vendorid = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->booking->customer_id;

				// dd($check_vendorid,$customer_id);
				if ($check_vendorid == $customer_id) { //ensuring searched vendor data inserted
					$ref_no = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->getRefNo->ref_no; //reference no or transactionid
					// dd(123);
					$prep = [
						"id" => $bp->id,
						"transaction_id" => $ref_no,
						"type" => 23, // credit
						"from_id" => $bp->id,
						"param_id" => $parameter_id,
						"bank_id" => $bp->bank_id,
						"advance_for" => null,
						"from_transaction" => null,
						"total" => $bp->amount,
						"is_completed" => null,
						"created_at" => date("Y-m-d H:i:s", strtotime($bp->created_at)),
						"updated_at" => date("Y-m-d H:i:s", strtotime($bp->updated_at)),
						"deleted_at" => null,
						"org_id" => $bp->cv_id,
						"date" => $bp->date,
						"is_bulk" => 1,
						// 'bulk_data' => $bp,
					];
					$baked = Helper::toCollection($prep);
					$transactions->push($baked);
				}
			}
		}

		$transactions = $transactions->where('org_id', $customer_id)->where('date', '!=', null)->sortBy('date')->values();
		$customer_balance = User::find($customer_id)->opening_balance;

		if (count($transactions)) {
			$first_date = count($transactions) ? $transactions->first()->date : null;
			// dd($transaction_ids);
			if (empty($from_date))
				$from_date  = $first_date;
			if (empty($to_date))
				$to_date  = $transactions->reverse()->first()->date;

			// dd($from_date,$to_date);

			$prev_transactions = $transactions->where('date', '<', $from_date)->values();
			$curr_transactions = $transactions->whereBetween('date', [$from_date, $to_date])->values();

			// dd($from_date,$to_date,$prev_transactions->reverse()->first(),$curr_transactions);

			//Opening Balance
			// $cr_total = $prev_transactions->where('type',23)->sum('total');
			$bulk_total = $prev_transactions->where('is_bulk', 1)->sum('total');
			$dr_total = $prev_transactions->where('is_bulk', '!=', 1)->sum('total');
			// dd($cr_total,$dr_total,$customer_balance,$prev_transactions);
			// $bulkPayment = BulkPayment::whereBetween('date',[$from_date,$to_date])->where('cv_id',$vendor_id)->get();
			$opening_balance = $op = bcdiv((($dr_total + $customer_balance) - ($bulk_total)), 1, 2);
			// dd($opening_balance);
			foreach ($curr_transactions as $bp) {
				if ($bp->is_bulk == 1)
					$bp->new_total = $op - $bp->total;
				else
					$bp->new_total = $op + $bp->total;

				$op = $bp->new_total;
			}
		} else {
			$opening_balance = $customer_balance;
			$curr_transactions = $transactions;
		}




		// dd($transactions->reverse()->toArray());
		$customers = DB::table('bookings')->join('users', 'bookings.customer_id', '=', 'users.id')->select('users.name as customer_name', 'users.id as customer_id')->groupBy('bookings.customer_id')->pluck('customer_name', 'customer_id');

		$index['transactions'] = $curr_transactions;
		$index['opening_balance'] = $opening_balance;
		$index['customers'] = $customers;
		$index['request'] = $request->all();
		$index['result'] = "";
		// dd($index);
		return view('reports.customerPayment', $index);
	}
	public function customer_payment(Request $request)
	{

		$customer_id = $request->customer_id;
		$from_date = $request->from_date;
		$to_date = $request->to_date;

		$transactions = Transaction::where(['param_id' => '18', 'type' => '23'])->where(function ($q) {
			$q->where('advance_for', '!=', '21')
				->orWhereRaw('transactions.advance_for IS NULL');
		})->get();
		// dd($transactions);
		foreach ($transactions as $t) {
			$shalom = Bookings::where('id', $t->from_id);
			$t->org_id = $shalom->exists() ? $shalom->first()->customer_id : null;
			$t->date = $shalom->exists() ? $shalom->first()->pickup : null;
			$t->is_bulk = null;
		}

		$transactions = $transactions->where('org_id', $customer_id)->where('date', '!=', null);
		// dd($transactions);
		$bulkPayment = BulkPayment::get();
		foreach ($bulkPayment as $bp) {
			$parameter_id = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->param_id;
			// dd($parameter_id);
			if ($parameter_id == 18) {
				//Vendor Check for each param_id
				$check_vendorid = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->booking->customer_id;

				// dd($check_vendorid,$customer_id);
				if ($check_vendorid == $customer_id) { //ensuring searched vendor data inserted
					$ref_no = empty($bp->single_bulk_list) ? null : $bp->single_bulk_list->transaction->getRefNo->ref_no; //reference no or transactionid
					// dd(123);
					$prep = [
						"id" => $bp->id,
						"transaction_id" => $ref_no,
						"type" => 23, // credit
						"from_id" => $bp->id,
						"param_id" => $parameter_id,
						"bank_id" => $bp->bank_id,
						"advance_for" => null,
						"from_transaction" => null,
						"total" => $bp->amount,
						"is_completed" => null,
						"created_at" => date("Y-m-d H:i:s", strtotime($bp->created_at)),
						"updated_at" => date("Y-m-d H:i:s", strtotime($bp->updated_at)),
						"deleted_at" => null,
						"org_id" => $bp->cv_id,
						"date" => $bp->date,
						"is_bulk" => 1,
						// 'bulk_data' => $bp,
					];
					$baked = Helper::toCollection($prep);
					$transactions->push($baked);
				}
			}
		}

		$transactions = $transactions->where('org_id', $customer_id)->where('date', '!=', null)->sortBy('date')->values();
		$customer_balance = User::find($customer_id)->opening_balance;

		if (count($transactions)) {
			$first_date = count($transactions) ? $transactions->first()->date : null;
			// dd($transaction_ids);
			if (empty($from_date))
				$from_date  = $first_date;
			if (empty($to_date))
				$to_date  = $transactions->reverse()->first()->date;

			// dd($from_date,$to_date);

			$prev_transactions = $transactions->where('date', '<', $from_date)->values();
			$curr_transactions = $transactions->whereBetween('date', [$from_date, $to_date])->values();

			// dd($from_date,$to_date,$prev_transactions->reverse()->first(),$curr_transactions);

			//Opening Balance
			// $cr_total = $prev_transactions->where('type',23)->sum('total');
			$bulk_total = $prev_transactions->where('is_bulk', 1)->sum('total');
			$dr_total = $prev_transactions->where('is_bulk', '!=', 1)->sum('total');
			// dd($cr_total,$dr_total,$customer_balance,$prev_transactions);
			// $bulkPayment = BulkPayment::whereBetween('date',[$from_date,$to_date])->where('cv_id',$vendor_id)->get();
			$opening_balance = $op = bcdiv((($dr_total + $customer_balance) - ($bulk_total)), 1, 2);
			// dd($opening_balance);
			foreach ($curr_transactions as $bp) {
				if ($bp->is_bulk == 1)
					$bp->new_total = $op - $bp->total;
				else
					$bp->new_total = $op + $bp->total;

				$op = $bp->new_total;
			}
		} else {
			$opening_balance = $customer_balance;
			$curr_transactions = $transactions;
		}




		// dd($transactions->reverse()->toArray());
		$customers = DB::table('bookings')->join('users', 'bookings.customer_id', '=', 'users.id')->select('users.name as customer_name', 'users.id as customer_id')->groupBy('bookings.customer_id')->pluck('customer_name', 'customer_id');

		$index['transactions'] = $curr_transactions;
		$index['opening_balance'] = $opening_balance;
		$index['from_date'] = $from_date;
		$index['to_date'] = $to_date;
		$index['customer_data'] = User::find($customer_id);
		$index['customers'] = $customers;
		$index['request'] = $request->all();
		$index['result'] = "";
		return view('reports.customerPaymentPrint', $index);
	}
	//OLD Report
	// public function booking() {

	// 	$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));

	// 	$y = array();
	// 	foreach ($years as $year) {
	// 		$y[$year->years] = $year->years;
	// 	}
	// 	$data['vehicle_select'] = "";
	// 	$data['customer_select'] = "";
	// 	$data['customers'] = User::where('user_type', 'C')->get();
	// 	$data['years'] = $y;
	// 	$data['year_select'] = date("Y");
	// 	$data['month_select'] = date("n");
	// 	if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
	// 		$data['vehicles'] = VehicleModel::get();
	// 	} else {
	// 		$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
	// 	}
	// 	$vehicle_ids = array(0);
	// 	foreach ($data['vehicles'] as $vehicle) {
	// 		$vehicle_ids[] = $vehicle->id;
	// 	}
	// 	$data['bookings'] = Bookings::whereYear("pickup", date("Y"))->whereMonth("pickup", date("n"))->whereIn('vehicle_id', $vehicle_ids)->get();

	// 	return view("reports.booking", $data);
	// }

	//debi added // Customer Grouped Details report
	// public function booking() 
	// {

	// 	$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));

	// 	$y = array();
	// 	foreach ($years as $year) {
	// 		$y[$year->years] = $year->years;
	// 	}
	// 	$data['vehicle_select'] = "";
	// 	$data['customer_select'] = "";
	// 	$data['customers'] = User::where('user_type', 'C')->get();
	// 	$data['years'] = $y;
	// 	$data['year_select'] = null;
	// 	$data['month_select'] = null;
	// 	if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
	// 		$data['vehicles'] = VehicleModel::get();
	// 	} else {
	// 		$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
	// 	}
	// 	$vehicle_ids = array(0);
	// 	foreach ($data['vehicles'] as $vehicle) {
	// 		$vehicle_ids[] = $vehicle->id;
	// 	}

	// 	// $data['bookings'] = $bookings = Bookings::whereYear("pickup", date("Y"))->whereMonth("pickup", date("n"))->whereIn('vehicle_id', $vehicle_ids)->whereYear("pickup", date("Y"))->groupBy('customer_id')->get();

	// 	// foreach($bookings as $book){ //Customer Loop

	// 	// 	$advpay_array = [];
	// 	// 	$payment_amount = [];
	// 	// 	$total_price = [];
	// 	// 	$custget = Bookings::where('customer_id',$book->customer_id)->get();

	// 	// 	foreach($custget as $bc){ // Particular Cusomter booking

	// 	// 		$advpay_array[] = floatval($bc->getMeta('advance_pay'));
	// 	// 		$payment_amount[] = floatval($bc->getMeta('payment_amount'));
	// 	// 		$total_price[] = floatval($bc->getMeta('total_price'));
	// 	// 	}
	// 	// 	$book->advpay_array = array_sum($advpay_array);
	// 	// 	$book->payment_amount = array_sum($payment_amount);
	// 	// 	$book->total_price = array_sum($total_price);

	// 	// }
	// 	return view("reports.booking", $data);
	// }

	public function booking()
	{
		$data['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)
			->pluck('vehicle_name', 'id');
		$data['customers'] = User::where('user_type', 'C')->pluck("name", "id");
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;

		return view("reports.booking", $data);
	}

	public function booking_post(Request $request)
	{
		$customer_id = $request->customer_id;
		$vehicle_id = $request->vehicle_id;
		$from_date = $request->date1;
		$to_date = $request->date2;
		$from_date = empty($from_date) ? Bookings::orderBy('pickup', 'asc')->take(1)->first('pickup')->pickup : $from_date;
		$to_date = empty($to_date) ? Bookings::orderBy('pickup', 'desc')->take(1)->first('pickup')->pickup : $to_date;
		// $abc['vendor_id'] = $vendor_id;
		// $abc['fuel_type'] = $fuel_type;
		// $abc['from_date'] = $from_date;
		// $abc['to_date'] = $to_date;
		// dd($abc);
		//same date search
		$from_date = Helper::ymd($from_date);
		$to_date = Helper::ymd($to_date);
		if (empty($vehicle_id) && empty($customer_id))
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date]);
		elseif (empty($vehicle_id))
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date])->where('customer_id', $customer_id);
		elseif (empty($customer_id))
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date])->where('vehicle_id', $vehicle_id);
		else
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date])->where(['vehicle_id' => $vehicle_id, 'customer_id' => $customer_id]);
		$total = array();
		foreach ($bookings->get() as $bk) {
			$total[] = $bk->getMeta('total_price');
			$totalfuel[] = $bk->getMeta('pet_required');
			$fodderfuel[] = $bk->getMeta('fodder_consumption');
			$totaldistance[] = !empty($bk->getMeta('distance')) ? explode(" ", $bk->getMeta('distance'))[0] : 0;
			$fodderdistance[] = !empty($bk->getMeta('fodder_km')) ? explode(" ", $bk->getMeta('fodder_km'))[0] : 0;
		}

		$index['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)->pluck('vehicle_name', 'id');
		$index['customers'] = User::where('user_type', 'C')->pluck("name", "id");
		$index['bookings'] = $bookings->orderBy('id', 'DESC')->get();
		$index['result'] = '';
		$data['date1'] = null;
		$data['date2'] = null;
		$index['total_price'] = array_sum($total);
		$index['total_fuel'] = array_sum($totalfuel);
		$index['total_distance'] = array_sum($totaldistance);
		$index['fodderfuel'] = array_sum($fodderfuel);
		$index['fodderdistance'] = array_sum($fodderdistance);
		$index['request'] = $request->all();
		$index['loadset'] = Params::where('code', 'LoadSetting')->pluck('label', 'id');
		// dd($bookings->meta()->get());
		// dd($bookings->meta()->get());
		// dd($index['bookings']->first());
		// dd($index);
		return view("reports.booking", $index);
	}

	public function view_booking_details($arr)
	{
		//dd('xxxxxx');
		$arr = explode(",", $arr);
		$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));

		$y = array();
		$data['customers'] = User::where('user_type', 'C')->get();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		$data['vehicle_select'] = $arr[1];
		$data['customer_select'] = $arr[0];
		$data['years'] = $y;
		$data['year_select'] = $arr[3];
		$data['month_select'] = $arr[2];
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}


		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}


		if (empty($arr[3]) && !empty($arr[2]))
			$data['bookings'] = Bookings::whereMonth("pickup", $arr[2])->whereIn('vehicle_id', $vehicle_ids);
		elseif (!empty($arr[3]) && empty($arr[2]))
			$data['bookings'] = Bookings::whereYear("pickup", $arr[3])->whereIn('vehicle_id', $vehicle_ids);
		elseif (!empty($arr[3]) && !empty($arr[2]))
			$data['bookings'] = Bookings::whereYear("pickup", $arr[3])->whereMonth("pickup", $arr[2])->whereIn('vehicle_id', $vehicle_ids);
		else
			$data['bookings'] = Bookings::whereIn('vehicle_id', $vehicle_ids);
		//dd($data['bookings']->get());

		if (!empty($arr[1]) && !empty($arr[0])) {
			$data['bookings'] = $data['bookings']->where(["vehicle_id" => $arr[1], "customer_id" => $arr[0]]);
		} elseif (empty($arr[1]) && !empty($arr[0])) {
			$data['bookings'] = $data['bookings']->where("customer_id", $arr[0]);
		} elseif (!empty($arr[1]) && empty($arr[0])) {
			$data['bookings'] = $data['bookings']->where("vehicle_id", $arr[1]);
		}
		$bookList = $data['bookings']->get();
		$data['bookings'] = $bookList;
		//$bookListCust = $data['bookings']->groupBy('customer_id')->get();
		return view("reports.view_booking_details", $data);
	}
	public function print_booking_details(Request $request)
	{
		//dd($request->all());
		//$arr = explode(",",$arr);
		$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));

		$y = array();
		$data['customers'] = User::where('user_type', 'C')->get();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		$data['vehicle_select'] = $request->vehicle_id;
		$data['customer_select'] = $request->customer_id;
		$data['years'] = $y;
		$data['year_select'] = $request->year;
		$data['month_select'] = $request->month;
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}


		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}


		if (empty($request->year) && !empty($request->month))
			$data['bookings'] = Bookings::whereMonth("pickup", $request->month)->whereIn('vehicle_id', $vehicle_ids);
		elseif (!empty($request->year) && empty($request->month))
			$data['bookings'] = Bookings::whereYear("pickup", $request->year)->whereIn('vehicle_id', $vehicle_ids);
		elseif (!empty($request->year) && !empty($request->month))
			$data['bookings'] = Bookings::whereYear("pickup", $request->year)->whereMonth("pickup", $request->month)->whereIn('vehicle_id', $vehicle_ids);
		else
			$data['bookings'] = Bookings::whereIn('vehicle_id', $vehicle_ids);
		//dd($data['bookings']->get());

		if (!empty($request->vehicle_id) && !empty($request->customer_id)) {
			$data['bookings'] = $data['bookings']->where(["vehicle_id" => $request->vehicle_id, "customer_id" => $request->customer_id]);
		} elseif (empty($request->vehicle_id) && !empty($request->customer_id)) {
			$data['bookings'] = $data['bookings']->where("customer_id", $request->customer_id);
		} elseif (!empty($request->vehicle_id) && empty($request->customer_id)) {
			$data['bookings'] = $data['bookings']->where("vehicle_id", $request->vehicle_id);
		}
		$bookList = $data['bookings']->get();
		$data['bookings'] = $bookList;
		//$bookListCust = $data['bookings']->groupBy('customer_id')->get();
		return view("reports.view_booking_details_print", $data);
	}
	public function delinquent_post(Request $request)
	{

		$years = DB::select(DB::raw("select distinct year(date) as years from income where deleted_at is null order by years desc"));
		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		foreach ($data['vehicles'] as $row) {
			$data['v'][$row['id']] = $row;
		}

		$data['vehicle_id'] = $request->get("vehicle_id");

		$income = IncomeModel::select(['vehicle_id', 'income_cat', 'date', DB::raw('sum(amount) as Income2,dayname(date) as day')])->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->groupBy('date')->orderBy('date');
		if ($data['vehicle_id'] != "") {
			$data['data'] = $income->where('vehicle_id', $data['vehicle_id'])->get();
		} else {
			$data['data'] = $income->whereIn('vehicle_id', $vehicle_ids)->get();
		}

		$data['years'] = $y;
		$data['result'] = "";

		return view("reports.delinquent", $data);
	}

	/*
		public function parts() {
				$data['parts'] = PartsModel::get();

				return view("reports.parts", $data);
			}
			public function parts_post(Request $request) {
				$data['parts'] = PartsModel::get();
				$data['parts2'] = TransactionModel::wherePart_id($request->get("part"))->get();

				$data['result'] = "";
				return view("reports.parts", $data);
			}
	*/

	public function monthly_post(Request $request)
	{

		$years = DB::select(DB::raw("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc"));
		$y = array();
		$b = array();
		$i = array();
		$c = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		$data['vehicle_select'] = $request->get("vehicle_id");

		$income1 = IncomeModel::select(DB::raw("SUM(amount) as income"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereIn('vehicle_id', $vehicle_ids);
		$expense1 = Expense::select(DB::raw("SUM(amount) as expense"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereIn('vehicle_id', $vehicle_ids);
		$expense2 = Expense::select("type", "expense_type", DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereIn('vehicle_id', $vehicle_ids)->groupBy(['expense_type', 'type']);
		$income2 = IncomeModel::select("income_cat", DB::raw("sum(amount) as amount"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->whereIn('vehicle_id', $vehicle_ids)->groupBy(['income_cat']);
		if ($data['vehicle_select'] != "") {
			$data['income'] = $income1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expenses'] = $expense1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expense_by_cat'] = $expense2->where('vehicle_id', $data['vehicle_select'])->get();
			$data['income_by_cat'] = $income2->where('vehicle_id', $data['vehicle_select'])->get();
		} else {
			$data['income'] = $income1->get();
			$data['expenses'] = $expense1->get();
			$data['expense_by_cat'] = $expense2->get();
			$data['income_by_cat'] = $income2->get();
		}

		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}

		$kk = ExpCats::get();

		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();

		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;

		$data['years'] = $y;
		$data['result'] = "";
		return view("reports.monthly", $data);
	}

	public function fuel()
	{
		// dd($_POST);

		$data['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)
			->pluck('vehicle_name', 'id');
		// dd(FuelType::pluck('fuel_name','id'));
		// dd($data['fuel']);
		$data['vehicle_id'] = "";
		$data['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['months'] = Helper::getMonths(true);
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;
		// dd($data);
		return view('reports.fuel', $data);
	}

	public function fuel_post(Request $request)
	{

		// dd($request->all());

		$data['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)
			->pluck('vehicle_name', 'id');

		$vehicle_id = $request->get('vehicle_id');
		$fuel_type = $request->get('fuel_type');
		$data['vehicle_id'] = $vehicle_id;
		$data['vehicle_id'] = $vehicle_id;
		if ($request->get('date1') == null)
			$start = FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		// dd($start);
		// dd($end);



		if (!empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where(['vehicle_id' => $vehicle_id, 'fuel_type' => $fuel_type])->whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		} else if (empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('fuel_type', $fuel_type)->whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		} else if (!empty($vehicle_id) && empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('vehicle_id', $vehicle_id)->whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		} else {
			$data['fuel'] = FuelModel::whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		}


		foreach ($data['fuel'] as $f) {
			$f->total = empty($f->total) ? $f->qty * $f->cost_per_unit : $f->total;
			$f->gtotal = empty($f->grand_total) ? $f->total : $f->grand_total;
		}

		// Total KM
		if (!empty($vehicle_id)) {
			$bookingdata = Bookings::where('vehicle_id', $vehicle_id)->whereBetween('pickup', [$start, $end])->get();
		} else {
			$bookingdata = Bookings::whereBetween('pickup', [$start, $end])->get();
		}
		$total_km = [];
		foreach ($bookingdata as $bd) {
			$total_km[] = Helper::removeKM($bd->getMeta('distance'));
		}

		// Helper::totalBookingIncome($vehicle_id);
		// $data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])->whereBetween('created_at', [$start, $end])->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();
		// dd();
		$data['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$data['fuel_totalprice'] = $data['fuel']->sum('total');
		$data['fuel_totalqty'] = $data['fuel']->sum('qty');
		$data['total_km'] = array_sum($total_km);
		$data['result'] = "";
		$data['request'] = $request->all();
		$data['months'] = Helper::getMonths(true);
		$data['dates'] = [$start, $end];
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('reports.fuel', $data);
	}

	public function yearly()
	{
		$years = DB::select(DB::raw("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc"));
		$y = array();
		$c = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {

			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}

		$data['year_select'] = date("Y");

		$data['vehicle_select'] = null;
		$data['years'] = $y;
		$data['income'] = IncomeModel::select(DB::raw('sum(amount) as income'))->whereYear('date', date('Y'))->whereIn('vehicle_id', $vehicle_ids)->get();
		$data['expenses'] = Expense::select(DB::raw('sum(amount) as expense'))->whereYear('date', date("Y"))->whereIn('vehicle_id', $vehicle_ids)->get();
		$data['expense_by_cat'] = Expense::select(['type', 'expense_type', DB::raw('sum(amount) as expense')])->whereYear('date', date('Y'))->whereIn('vehicle_id', $vehicle_ids)->groupBy(['expense_type', 'type'])->get();
		$data['income_by_cat'] = IncomeModel::select(['income_cat', DB::raw('sum(amount) as amount')])->whereYear('date', date('Y'))->whereIn('vehicle_id', $vehicle_ids)->groupBy('income_cat')->get();

		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}

		$kk = ExpCats::get();

		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();

		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}

		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;
		$data['result'] = "";
		return view('reports.yearly', $data);
	}

	public function yearly_post(Request $request)
	{

		$years = DB::select(DB::raw("select distinct year(date) as years from income  union select distinct year(date) as years from expense order by years desc"));
		$y = array();
		$b = array();
		$i = array();
		$c = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$data['year_select'] = $request->get("year");
		$data['vehicle_select'] = $request->get("vehicle_id");

		$income1 = IncomeModel::select(DB::raw("sum(amount) as income"))->whereYear('date', $data['year_select']);
		$expense1 = Expense::select(DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select']);
		$expense2 = Expense::select("type", "expense_type", DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select'])->groupBy('expense_type', 'type');
		$income2 = IncomeModel::select('income_cat', DB::raw("sum(amount) as amount"))->whereYear('date', $data['year_select'])->groupBy('income_cat');
		if ($data['vehicle_select'] != "") {
			$data['income'] = $income1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expenses'] = $expense1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expense_by_cat'] = $expense2->where('vehicle_id', $data['vehicle_select'])->get();
			$data['income_by_cat'] = $income2->where('vehicle_id', $data['vehicle_select'])->get();
		} else {
			$data['income'] = $income1->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['expenses'] = $expense1->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['expense_by_cat'] = $expense2->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['income_by_cat'] = $income2->whereIn('vehicle_id', $vehicle_ids)->get();
		}

		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}

		$kk = ExpCats::get();

		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();

		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}

		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;

		$data['years'] = $y;
		$data['result'] = "";
		return view('reports.yearly', $data);
	}

	public function vendors()
	{

		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$details_parts = PartsModel::select(['vendor_id', DB::raw('sum(unit_cost) as total')])->groupBy('vendor_id');
		// dd($details_parts);
		$details_fuel = FuelModel::select(['vendor_name', DB::raw('sum(qty*cost_per_unit) as total')])->whereNotNull('vendor_name')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_name');
		//dd($details_fuel);
		$data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])
			->where('vendor_id', '<>', 7)
			->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')
			->union($details_fuel)->union($details_parts)->get();

		//dd($data);
		$kkgd = PartsModel::select('vendor_id')
			->whereNotNull('vendor_id')

			->groupBy('vendor_id');
		$kkg = FuelModel::select('vendor_name')
			->whereNotNull('vendor_name')
			->whereIn('vehicle_id', $vehicle_ids)
			->groupBy('vendor_name');
		$kk = WorkOrders::select('vendor_id')
			->whereIn('vehicle_id', $vehicle_ids)
			->where('vendor_id', '<>', 7)
			->groupBy('vendor_id')->union($kkg)->union($kkgd)->get();

		$b = array();
		foreach ($kk as $k) {
			$b[$k->vendor_id] = $k->vendor->name;
		}
		$combineArray = [];
		// dd($data);
		foreach ($data['details'] as $value) {
			// dd($value);
			if (Vendor::withTrashed()->where('id', $value->vendor_id)->first('name')->exists())
				$vendorName = Vendor::withTrashed()->where('id', $value->vendor_id)->first('name')->name;
			else
				$vendorName = 'N/A';

			if (empty($combineArray[$value->vendor_id])) {
				$combineArray[$value->vendor_id]['name'] = $vendorName;
				$combineArray[$value->vendor_id]['total'] = $value->total;
			} else {
				// dd($value->vendor_id);
				$combineArray[$value->vendor_id]['name'] = $vendorName;
				$combineArray[$value->vendor_id]['total'] = $combineArray[$value->vendor_id]['total'] + $value->total;
			}
		}

		$data['vendors'] = $b;
		$data['graph'] = collect($combineArray);
		$data['date1'] = null;
		$data['date2'] = null;
		// dd($data);
		return view('reports.vendor', $data);
	}

	public function vendors_post(Request $request)
	{

		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);

		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$start = date('Y-m-d H:i:s', strtotime($request->get('date1')));

		$end = date('Y-m-d H:i:s', strtotime($request->get('date2')));

		$details_parts = PartsModel::select(['vendor_id', DB::raw('sum(unit_cost) as total')])->groupBy('vendor_id');

		$details_fuel = FuelModel::select(['vendor_name', DB::raw('sum(qty*cost_per_unit) as total')])->whereNotNull('vendor_name')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_name');

		$data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])
			->whereBetween('created_at', [$start, $end])
			->whereIn('vehicle_id', $vehicle_ids)
			->where('vendor_id', '<>', 7)
			->groupBy('vendor_id')->union($details_fuel)
			->union($details_parts)->get();

		$kkgd = PartsModel::select('vendor_id')->whereNotNull('vendor_id')->groupBy('vendor_id');
		$kkg = FuelModel::select('vendor_name')->whereNotNull('vendor_name')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_name');
		$kk = WorkOrders::select('vendor_id')
			->whereIn('vehicle_id', $vehicle_ids)
			->where('vendor_id', '<>', 7)
			->groupBy('vendor_id')
			->union($kkg)->union($kkgd)->get();

		$b = array();
		foreach ($kk as $k) {
			$b[$k->vendor_id] = $k->vendor->name;
		}

		$combineArray = [];
		foreach ($data['details'] as $value) {

			if (Vendor::where('id', $value->vendor_id)->first('name')->exists())
				$vendorName = Vendor::where('id', $value->vendor_id)->first('name')->name;
			else
				$vendorName = 'N/A';

			if (empty($combineArray[$value->vendor_id])) {
				$combineArray[$value->vendor_id]['name'] = $vendorName;
				$combineArray[$value->vendor_id]['total'] = $value->total;
			} else {

				$combineArray[$value->vendor_id]['name'] = $vendorName;
				$combineArray[$value->vendor_id]['total'] = $combineArray[$value->vendor_id]['total'] + $value->total;
			}
		}

		$data['vendors'] = $b;
		$data['date1'] = $request->date1;
		$data['date2'] = $request->date2;
		$data['graph'] = collect($combineArray);
		return view('reports.vendor', $data);
	}

	public function drivers()
	{

		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		// data of current month and current year
		$drivers = Bookings::select(['id', 'driver_id', 'vehicle_id'])->where('status', 1)->whereIn('vehicle_id', $vehicle_ids)->groupBy('driver_id')->get();
		$drivers_by_year = array();
		foreach ($drivers as $d) {
			$drivers_by_year[$d->driver->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", date("Y"))->where('driver_id', $d->driver_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		// dd($drivers_by_year);
		$data['drivers_by_year'] = $drivers_by_year;
		$drivers_by_month = array();
		foreach ($drivers as $d) {
			$drivers_by_month[$d->driver->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", date("Y"))->whereMonth("bookings.updated_at", date("n"))->where('driver_id', $d->driver_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		$data['drivers_by_month'] = $drivers_by_month;
		// dd($drivers_by_month);
		$years = collect(DB::select("select distinct year(created_at) as years from bookings where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}

		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['years'] = $y;
		return view('reports.driver', $data);
	}

	public function drivers_post(Request $request)
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		// data of selected month and year
		$drivers = Bookings::select(['id', 'driver_id', 'vehicle_id'])->where('status', 1)->whereIn('vehicle_id', $vehicle_ids)->groupBy('driver_id')->get();
		$drivers_by_year = array();
		foreach ($drivers as $d) {
			$drivers_by_year[$d->driver->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get("year"))->where('driver_id', $d->driver_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		// dd($drivers_by_year);
		$data['drivers_by_year'] = $drivers_by_year;

		$drivers_by_month = array();
		foreach ($drivers as $d) {
			$drivers_by_month[$d->driver->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get('year'))->whereMonth("bookings.updated_at", $request->get("month"))->where('driver_id', $d->driver_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}

		// dd($drivers_by_month);
		$data['drivers_by_month'] = $drivers_by_month;

		$years = collect(DB::select("select distinct year(created_at) as years from bookings where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		$data['years'] = $y;

		return view('reports.driver', $data);
	}

	public function customers()
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		// data of current month and current year
		$customers = Bookings::select(['id', 'customer_id', 'vehicle_id'])->where('status', 1)->whereIn('vehicle_id', $vehicle_ids)->groupBy('customer_id')->get();
		$customers_by_year = array();
		foreach ($customers as $d) {
			$customers_by_year[$d->customer->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", date("Y"))->where('customer_id', $d->customer_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}

		$data['customers_by_year'] = $customers_by_year;
		arsort($customers_by_year);
		$data['top10'] = array_slice($customers_by_year, 0, 10);

		$customers_by_month = array();
		foreach ($customers as $d) {
			$customers_by_month[$d->customer->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", date("Y"))->whereMonth("bookings.updated_at", date("n"))->where('customer_id', $d->customer_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		$data['customers_by_month'] = $customers_by_month;
		$years = collect(DB::select("select distinct year(created_at) as years from bookings where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}

		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['years'] = $y;
		return view('reports.customer', $data);
	}

	public function customers_post(Request $request)
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		// data of selected month and year
		$customers = Bookings::select(['id', 'customer_id', 'vehicle_id'])->where('status', 1)->whereIn('vehicle_id', $vehicle_ids)->groupBy('customer_id')->get();
		$customers_by_year = array();
		foreach ($customers as $d) {
			$customers_by_year[$d->customer->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get("year"))->where('customer_id', $d->customer_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		$data['customers_by_year'] = $customers_by_year;

		arsort($customers_by_year);
		$data['top10'] = array_slice($customers_by_year, 0, 10);
		$customers_by_month = array();
		foreach ($customers as $d) {
			$customers_by_month[$d->customer->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get("year"))->whereMonth("bookings.updated_at", $request->get("month"))->where('customer_id', $d->customer_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		$data['customers_by_month'] = $customers_by_month;

		$years = collect(DB::select("select distinct year(created_at) as years from bookings where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		$data['years'] = $y;

		return view('reports.customer', $data);
	}

	public function print_deliquent(Request $request)
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		foreach ($data['vehicles'] as $row) {
			$data['v'][$row['id']] = $row;
		}

		$data['vehicle_id'] = $request->get("vehicle_id");
		$income = IncomeModel::select(['vehicle_id', 'income_cat', 'date', DB::raw('sum(amount) as Income2,dayname(date) as day')])->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->groupBy('date')->orderBy('date');
		if ($data['vehicle_id'] != "") {
			$data['data'] = $income->where('vehicle_id', $data['vehicle_id'])->get();
		} else {
			$data['data'] = $income->whereIn('vehicle_id', $vehicle_ids)->get();
		}

		$data['vehicle'] = VehicleModel::find($request->get('vehicle_id'));
		return view('reports.print_delinquent', $data);
	}

	public function print_monthly(Request $request)
	{
		$b = array();
		$i = array();
		$c = array();

		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		$data['vehicle_select'] = $request->get("vehicle_id");
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}

		$income1 = IncomeModel::select(DB::raw("SUM(amount) as income"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select']);
		$expense1 = Expense::select(DB::raw("SUM(amount) as expense"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select']);
		$expense2 = Expense::select("type", "expense_type", DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->groupBy(['expense_type', 'type']);
		$income2 = IncomeModel::select("income_cat", DB::raw("sum(amount) as amount"))->whereYear('date', $data['year_select'])->whereMonth('date', $data['month_select'])->groupBy(['income_cat']);
		if ($data['vehicle_select'] != "") {
			$data['income'] = $income1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expenses'] = $expense1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expense_by_cat'] = $expense2->where('vehicle_id', $data['vehicle_select'])->get();
			$data['income_by_cat'] = $income2->where('vehicle_id', $data['vehicle_select'])->get();
		} else {
			$data['income'] = $income1->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['expenses'] = $expense1->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['expense_by_cat'] = $expense2->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['income_by_cat'] = $income2->whereIn('vehicle_id', $vehicle_ids)->get();
		}

		$kk = ExpCats::get();
		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}

		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}

		$hh = IncCats::get();

		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}
		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;

		$data['vehicle'] = VehicleModel::find($request->get("vehicle_id"));

		return view('reports.print_monthly', $data);
	}

	public function print_booking(Request $request)
	{
		$customer_id = $request->customer_id;
		$vehicle_id = $request->vehicle_id;
		$from_date = $request->date1;
		$to_date = $request->date2;
		$from_date = empty($from_date) ? Bookings::orderBy('pickup', 'asc')->take(1)->first('pickup')->pickup : $from_date;
		$to_date = empty($to_date) ? Bookings::orderBy('pickup', 'desc')->take(1)->first('pickup')->pickup : $to_date;
		// $abc['vendor_id'] = $vendor_id;
		// $abc['fuel_type'] = $fuel_type;
		// $abc['from_date'] = $from_date;
		// $abc['to_date'] = $to_date;
		// dd($abc);
		//same date search
		if (strtotime($from_date) == strtotime($to_date)) {
			$from_date = $from_date . " 00:00:00";
			$to_date = $to_date . " 23:59:59";
		}
		if (empty($vehicle_id) && empty($customer_id))
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date]);
		elseif (empty($vehicle_id))
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date])->where('customer_id', $customer_id);
		elseif (empty($customer_id))
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date])->where('vehicle_id', $vehicle_id);
		else
			$bookings = Bookings::whereBetween('pickup', [$from_date, $to_date])->where(['vehicle_id' => $vehicle_id, 'customer_id' => $customer_id]);
		$total = array();
		foreach ($bookings->get() as $bk) {
			$total[] = $bk->getMeta('total_price');
			$totalfuel[] = $bk->getMeta('pet_required');
			$fodderfuel[] = $bk->getMeta('fodder_consumption');
			$totaldistance[] = !empty($bk->getMeta('distance')) ? explode(" ", $bk->getMeta('distance'))[0] : 0;
			$fodderdistance[] = !empty($bk->getMeta('fodder_km')) ? explode(" ", $bk->getMeta('fodder_km'))[0] : 0;
		}

		$index['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)
			->pluck('vehicle_name', 'id');

		$index['customers'] = User::where('user_type', 'C')->pluck("name", "id");
		$index['bookings'] = $bookings->orderBy('id', 'DESC')->get();
		$index['result'] = '';
		$index['date1'] = null;
		$index['date2'] = null;
		$index['total_price'] = array_sum($total);
		$index['total_fuel'] = array_sum($totalfuel);
		$index['total_distance'] = array_sum($totaldistance);
		$index['fodderfuel'] = array_sum($fodderfuel);
		$index['fodderdistance'] = array_sum($fodderdistance);
		$index['request'] = $request->all();
		$index['loadset'] = Params::where('code', 'LoadSetting')->pluck('label', 'id');
		// dd($index);
		return view('reports.print_bookings', $index);
	}

	public function print_fuel(Request $request)
	{
		// dd($request->all());

		$vehicle_id = $request->get('vehicle_id');
		$fuel_type = $request->get('fuel_type');
		$data['vehicle_id'] = $vehicle_id;
		$data['vehicle_id'] = $vehicle_id;
		if ($request->get('date1') == null)
			$start = FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		// dd($start);
		// dd($end);



		if (!empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where(['vehicle_id' => $vehicle_id, 'fuel_type' => $fuel_type])->whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		} else if (empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('fuel_type', $fuel_type)->whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		} else if (!empty($vehicle_id) && empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('vehicle_id', $vehicle_id)->whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		} else {
			$data['fuel'] = FuelModel::whereBetween('date', [$start, $end])->orderBy("date", "DESC")->get();
		}


		foreach ($data['fuel'] as $f) {
			$f->total = empty($f->total) ? $f->qty * $f->cost_per_unit : $f->total;
			$f->gtotal = empty($f->grand_total) ? $f->total : $f->grand_total;
		}

		// Total KM
		if (!empty($vehicle_id)) {
			$bookingdata = Bookings::where('vehicle_id', $vehicle_id)->whereBetween('pickup', [$start, $end])->get();
		} else {
			$bookingdata = Bookings::whereBetween('pickup', [$start, $end])->get();
		}
		$total_km = [];
		foreach ($bookingdata as $bd) {
			$total_km[] = Helper::removeKM($bd->getMeta('distance'));
		}

		// Helper::totalBookingIncome($vehicle_id);
		// $data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])->whereBetween('created_at', [$start, $end])->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();
		// dd();
		$data['vehicle'] = !empty($vehicle_id) ? VehicleModel::find($vehicle_id) : null;
		$data['fuelType'] = !empty($fuel_type) ? FuelType::find($fuel_type) : null;
		$data['date'] = date("Y-m-d H:i:s");
		$data['result'] = "";
		$data['request'] = $request->all();
		$data['fuel_totalprice'] = $data['fuel']->sum('total');
		$data['fuel_totalqty'] = $data['fuel']->sum('qty');
		$data['total_km'] = array_sum($total_km);
		$data['vehicle'] = VehicleModel::find($request->get('vehicle_id'));
		$data['from_date'] = Helper::getCanonicalDate($start);
		$data['to_date'] = Helper::getCanonicalDate($end);
		$data['grand_total'] = 0;

		// dd($data);
		return view('reports.print_fuel', $data);
	}

	public function print_yearly(Request $request)
	{

		$b = array();
		$i = array();
		$c = array();

		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}

		$data['year_select'] = $request->get("year");
		$data['vehicle_select'] = $request->get("vehicle_id");

		$income1 = IncomeModel::select(DB::raw("sum(amount) as income"))->whereYear('date', $data['year_select']);
		$expense1 = Expense::select(DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select']);
		$expense2 = Expense::select("type", "expense_type", DB::raw("sum(amount) as expense"))->whereYear('date', $data['year_select'])->groupBy('expense_type', 'type');
		$income2 = IncomeModel::select('income_cat', DB::raw("sum(amount) as amount"))->whereYear('date', $data['year_select'])->groupBy('income_cat');
		if ($data['vehicle_select'] != "") {
			$data['income'] = $income1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expenses'] = $expense1->where('vehicle_id', $data['vehicle_select'])->get();
			$data['expense_by_cat'] = $expense2->where('vehicle_id', $data['vehicle_select'])->get();
			$data['income_by_cat'] = $income2->where('vehicle_id', $data['vehicle_select'])->get();
		} else {
			$data['income'] = $income1->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['expenses'] = $expense1->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['expense_by_cat'] = $expense2->whereIn('vehicle_id', $vehicle_ids)->get();
			$data['income_by_cat'] = $income2->whereIn('vehicle_id', $vehicle_ids)->get();
		}

		$ss = ServiceItemsModel::get();
		foreach ($ss as $s) {
			$c[$s->id] = $s->description;
		}

		$kk = ExpCats::get();

		foreach ($kk as $k) {
			$b[$k->id] = $k->name;
		}
		$hh = IncCats::get();

		foreach ($hh as $k) {
			$i[$k->id] = $k->name;
		}

		$data['service'] = $c;
		$data['expense_cats'] = $b;
		$data['income_cats'] = $i;

		$data['vehicle'] = VehicleModel::find($request->get('vehicle_id'));

		return view('reports.print_yearly', $data);
	}

	public function print_driver(Request $request)
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}

		$drivers = Bookings::select(['id', 'driver_id', 'vehicle_id'])->whereIn('vehicle_id', $vehicle_ids)->where('status', 1)->groupBy('driver_id')->get();

		$drivers_by_month = array();
		foreach ($drivers as $d) {
			$drivers_by_month[$d->driver->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get('year'))->whereMonth("bookings.updated_at", $request->get("month"))->where('driver_id', $d->driver_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}

		$data['drivers_by_month'] = $drivers_by_month;

		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");

		return view('reports.print_driver', $data);
	}

	public function print_vendor()
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();

		$kk = WorkOrders::select('vendor_id')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();
		$b = array();
		foreach ($kk as $k) {
			$b[$k->vendor_id] = $k->vendor->name;
		}
		$data['vendors'] = $b;

		return view('reports.print_vendor', $data);
	}

	public function print_customer(Request $request)
	{
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$customers = Bookings::select(['id', 'customer_id', 'vehicle_id'])->where('status', 1)->whereIn('vehicle_id', $vehicle_ids)->groupBy('customer_id')->get();
		$customers_by_year = array();
		foreach ($customers as $d) {
			$customers_by_year[$d->customer->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get("year"))->where('customer_id', $d->customer_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}
		$data['customers_by_year'] = $customers_by_year;

		arsort($customers_by_year);
		$data['top10'] = array_slice($customers_by_year, 0, 10);

		$customers_by_month = array();
		foreach ($customers as $d) {
			$customers_by_month[$d->customer->name] = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'tax_total');
				})->whereYear("bookings.updated_at", $request->get("year"))->whereMonth("bookings.updated_at", $request->get("month"))->where('customer_id', $d->customer_id)->whereIn('vehicle_id', $vehicle_ids)->sum('value');
		}

		$data['customers_by_month'] = $customers_by_month;

		$years = collect(DB::select("select distinct year(created_at) as years from bookings where deleted_at is null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		$data['years'] = $y;
		return view('reports.print_customer', $data);
	}

	public function users()
	{
		$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"))->toArray();

		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}

		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		$data['users'] = User::where('user_type', 'O')->orWhere('user_type', 'S')->get();
		$data['user_id'] = "";
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['years'] = $y;
		return view('reports.users', $data);
	}

	public function users_post(Request $request)
	{
		$years = DB::select(DB::raw("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));
		$y = array();
		foreach ($years as $year) {
			$y[$year->years] = $year->years;
		}
		if ($years == null) {
			$y[date('Y')] = date('Y');
		}
		$data['users'] = User::where('user_type', 'O')->orWhere('user_type', 'S')->get();
		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");

		$data['user_id'] = $request->get("user_id");
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}

		$data['data'] = Bookings::whereYear('pickup', $data['year_select'])->whereMonth('pickup', $data['month_select'])->where('user_id', $request->get('user_id'))->whereIn('vehicle_id', $vehicle_ids)->get();

		$data['years'] = $y;
		$data['result'] = "";

		return view("reports.users", $data);
	}

	public function print_users(Request $request)
	{

		$data['year_select'] = $request->get("year");
		$data['month_select'] = $request->get("month");
		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$data['data'] = Bookings::whereYear('pickup', $data['year_select'])->whereMonth('pickup', $data['month_select'])->where('user_id', $request->get('user_id'))->whereIn('vehicle_id', $vehicle_ids)->get();

		return view('reports.print_users', $data);
	}

	public function trashSearch()
	{
		// dd("search");
		// dd(Hash::make('password'));
		$index['from'] = Params::where('code', 'PaymentFrom')->pluck('label', 'id');
		$index['advance'] = Params::where('code', 'AdvanceFor')->pluck('label', 'id');
		$index['method'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$index['type'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$index['is_complete'] = [1 => 'Completed', 2 => 'In Progress', 3 => 'Not Assigned'];
		$index['order_by'] = ['id' => 'Transaction ID', 'total' => 'Amount'];
		$index['sort'] = [2 => 'Higher to Lower', 1 => 'Lower to Higher'];
		$index['bank'] = BankAccount::pluck('bank', 'id');
		$index['advance_for'] = Params::where('code', 'AdvanceFor')->pluck('label', 'id');
		$index['request'] = null;
		// dd($index);
		return view('reports.transaction', $index);
	}

	public function trashSearch_post(Request $request)
	{
		// dd($request->all());
		$transaction_id = $request->transaction_id;
		$from = $request->from;
		$type = $request->type;
		$is_complete = $request->is_complete;
		$bank = $request->bank;
		$advance_for = $request->advance_for;
		$order_by = $request->order_by;
		$sort = $request->sort == 1 ? "ASC" : "DESC";
		$from_date = !empty($request->from_date) ? date("Y-m-d", strtotime($request->from_date)) : null;
		$to_date = !empty($request->to_date) ? date("Y-m-d", strtotime($request->to_date)) : null;
		$from_date = empty($from_date) ? Transaction::orderBy('created_at', 'asc')->take(1)->first('created_at')->created_at : $from_date;
		$to_date = empty($to_date) ? Transaction::orderBy('created_at', 'desc')->take(1)->first('created_at')->created_at : $to_date;

		if ($request->has('transaction_id') && !empty($transaction_id)) {
			if (!empty($advance_for) && Helper::string_exists("BO", $transaction_id))
				$tr = Transaction::where("transaction_id", $transaction_id)->where('advance_for', $advance_for);
			else
				$tr = Transaction::where("transaction_id", $transaction_id);
			$tr = $tr->get();
		} else {
			$whr = array();
			if (!empty($from)) {
				$fromStr = " param_id ='$from' ";
				array_push($whr, $fromStr);
			}
			if (!empty($type)) {
				$typeStr = " type ='$type' ";
				array_push($whr, $typeStr);
			}
			if (!empty($is_complete)) {
				if (in_array($is_complete, [1, 2])) {
					$isComStr = " is_completed = '$is_complete'";
				} elseif ($is_complete == 3)
					$isComStr = " is_completed IS NULL ";
				array_push($whr, $isComStr);
			}
			if (!empty($advance_for) && !empty($from) && $from == 18) {
				$advanceForStr = " advance_for ='$advance_for' ";
				array_push($whr, $advanceForStr);
			}
			if (!empty($bank)) {
				$bankStr = " bank_id ='$bank' ";
				array_push($whr, $bankStr);
			}

			if (!empty($from_date) && !empty($to_date)) {
				$dateBetStr = " (created_at BETWEEN '$from_date' AND '$to_date') ";
				array_push($whr, $dateBetStr);
			}
			$where = implode(' AND ', $whr);

			$orderBy = "ORDER BY $order_by $sort";

			$qq = "select * from transactions where $where $orderBy";
			// dd($qq);
			$tr = Transaction::whereRaw($where)->orderBy($order_by, $sort)->get();
		}
		// dd($tr);
		$index['transaction'] = $tr;
		$index['totalTransaction'] = $index['transaction']->sum('total');
		// dd($qq);
		$index['from'] = Params::where('code', 'PaymentFrom')->pluck('label', 'id');
		$index['advance'] = Params::where('code', 'AdvanceFor')->pluck('label', 'id');
		$index['method'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$index['type'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$index['is_complete'] = [1 => 'Completed', 2 => 'In Progress', 3 => 'Not Assigned'];
		$index['order_by'] = ['id' => 'Transaction ID', 'total' => 'Amount'];
		$index['sort'] = [2 => 'Higher to Lower', 1 => 'Lower to Higher'];
		$index['bank'] = BankAccount::pluck('bank', 'id');
		$index['advance_for'] = Params::where('code', 'AdvanceFor')->pluck('label', 'id');
		$index['result'] = '';
		$index['request'] = $request->all();
		// dd($index);
		return view('reports.transaction', $index);
	}

	public function trashSearch_print(Request $request)
	{
		$transaction_id = $request->transaction_id;
		$from = $request->from;
		$type = $request->type;
		$is_complete = $request->is_complete;
		$bank = $request->bank;
		$advance_for = $request->advance_for;
		$order_by = $request->order_by;
		$sort = $request->sort == 1 ? "ASC" : "DESC";
		$from_date = !empty($request->from_date) ? date("Y-m-d", strtotime($request->from_date)) : null;
		$to_date = !empty($request->to_date) ? date("Y-m-d", strtotime($request->to_date)) : null;
		$from_date = empty($from_date) ? Transaction::orderBy('created_at', 'asc')->take(1)->first('created_at')->created_at : $from_date;
		$to_date = empty($to_date) ? Transaction::orderBy('created_at', 'desc')->take(1)->first('created_at')->created_at : $to_date;

		if ($request->has('transaction_id') && !empty($transaction_id)) {
			if (!empty($advance_for) && Helper::string_exists("BO", $transaction_id))
				$tr = Transaction::where("transaction_id", $transaction_id)->where('advance_for', $advance_for);
			else
				$tr = Transaction::where("transaction_id", $transaction_id);
			$from = $tr->first()->from;
			$tr = $tr->get();
		} else {
			$whr = array();
			if (!empty($from)) {
				$fromStr = " param_id ='$from' ";
				array_push($whr, $fromStr);
			}
			if (!empty($type)) {
				$typeStr = " type ='$type' ";
				array_push($whr, $typeStr);
			}
			if (!empty($is_complete)) {
				if (in_array($is_complete, [1, 2])) {
					$isComStr = " is_completed = '$is_complete'";
				} elseif ($is_complete == 3)
					$isComStr = " is_completed IS NULL ";
				array_push($whr, $isComStr);
			}
			if (!empty($advance_for) && !empty($from) && $from == 18) {
				$advanceForStr = " advance_for ='$advance_for' ";
				array_push($whr, $advanceForStr);
			}
			if (!empty($bank)) {
				$bankStr = " bank_id ='$bank' ";
				array_push($whr, $bankStr);
			}

			if (!empty($from_date) && !empty($to_date)) {
				$dateBetStr = " (created_at BETWEEN '$from_date' AND '$to_date') ";
				array_push($whr, $dateBetStr);
			}
			$where = implode(' AND ', $whr);

			$orderBy = "ORDER BY $order_by $sort";

			$qq = "select * from transactions where $where $orderBy";
			// dd($qq);
			$tr = Transaction::whereRaw($where)->orderBy($order_by, $sort)->get();
		}
		// dd($tr);
		foreach ($tr as $r) {
			$r->method = $r->income_expense->payment_method;
		}
		$index['transaction'] = $tr;
		$index['totalTransaction'] = $index['transaction']->sum('total');
		// dd($qq);
		// $index['from'] = !empty($from) ? Param::find($from)->label : null ;
		// $index['advance'] = Params::where('code','AdvanceFor')->pluck('label','id');
		// $index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
		// $index['type'] = Params::where('code','PaymentType')->pluck('label','id');
		// $index['is_complete'] = [1=>'Completed',2=>'In Progress',3=>'Not Assigned'];
		// $index['order_by'] = ['id'=>'Transaction ID','total'=>'Amount'];
		// $index['sort'] = [2=>'Descending',1=>'Ascending'];
		// $index['bank'] = BankAccount::pluck('bank','id');
		// $index['advance_for'] = Params::where('code','AdvanceFor')->pluck('label','id');
		// $index['result']='';
		$index['date'] = ['from_date' => $from_date, 'to_date' => $to_date];
		$index['request'] = $request->all();
		// dd($request->all());

		// dd($index);
		return view('reports.print_transaction', $index);
	}

	public function trashSearchBank()
	{
		dd("Bank Transact search");
	}

	public function trashSearchBank_post()
	{ }

	public function trashSearchBank_print()
	{ }

	public function driverspayroll()
	{
		// dd("Drivers Report");
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		$data['request'] = null;
		return view('reports.driver-report', $data);
	}

	public function driverspayroll_post(Request $request)
	{
		// dd("Drivers Post Report");
		// dd($request->all());
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		if ($request->get('date1') == null)
			$start = Payroll::orderBy('for_date', 'ASC')->take(1)->first('for_date')->for_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Payroll::orderBy('for_date', 'DESC')->take(1)->first('for_date')->for_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
		// dd($data);
		if (!empty($driver_id))
			$data['payroll'] = Payroll::where('user_id', $driver_id)->whereBetween('for_date', [$start, $end])->forDateDesc()->get();
		else
			$data['payroll'] = Payroll::whereBetween('for_date', [$start, $end])->forDateDesc()->get();
		// dd($data['payroll']);

		$data['total_salary'] = $data['payroll']->sum('salary');
		$data['payable_salary'] = $data['payroll']->sum('payable_salary');
		$data['advance'] = $data['total_salary'] - $data['payable_salary'];

		foreach ($data['payroll'] as $py) {
			$vehicle = $py->driver_vehicle;
			// dd($vehicle);
			if (!empty($vehicle) && !empty($vehicle->vehicle)) {
				$v = $vehicle->vehicle;
				$py->vehicle_det = $v->make . "-" . $v->model . "-" . $v->license_plate;
			} else
				$py->vehicle_det = "N/A";
		}
		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['request'] = $request->all();
		// dd($data);
		return view('reports.driver-report', $data);
	}
	public function driverspayroll_print(Request $request)
	{
		// dd($request->all());
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		if ($request->get('date1') == null)
			$start = Payroll::orderBy('for_date', 'ASC')->take(1)->first('for_date')->for_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Payroll::orderBy('for_date', 'DESC')->take(1)->first('for_date')->for_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
		// dd($data);
		if (!empty($driver_id))
			$data['payroll'] = Payroll::where('user_id', $driver_id)->whereBetween('for_date', [$start, $end])->forDateDesc()->get();
		else
			$data['payroll'] = Payroll::whereBetween('for_date', [$start, $end])->forDateDesc()->get();
		// dd($data['payroll']);

		$data['total_salary'] = $data['payroll']->sum('salary');
		$data['payable_salary'] = $data['payroll']->sum('payable_salary');
		$data['advance'] = $data['total_salary'] - $data['payable_salary'];

		foreach ($data['payroll'] as $py) {
			$vehicle = $py->driver_vehicle;
			// dd($vehicle);
			if (!empty($vehicle) && !empty($vehicle->vehicle) && empty($driver_id)) {
				$v = $vehicle->vehicle;
				$py->vehicle_det = $v->make . "-" . $v->model . "-" . $v->license_plate;
			} else
				$py->vehicle_det = "N/A";
		}
		//Selected Driver Name & Vehicle
		if (!empty($driver_id)) {
			$drmodel = DriverVehicleModel::where('driver_id', $driver_id)->first();
			$data['vehicleData'] = !empty($drmodel) ? $drmodel->vehicle : null;
		} else $data['vehicleData'] = null;
		$data['result'] = "";
		$data['driver_name'] = !empty($driver_id) ? User::find($driver_id)->name : null;
		$data['dates'] = [$start, $end];
		$data['from_date'] = Helper::getCanonicalDate($start, 'default');
		$data['to_date'] = Helper::getCanonicalDate($end, 'default');
		// dd($data);
		return view('reports.print-driver-report', $data);
	}

	public function driversAdvance()
	{
		// dd('Driver Advance');
		// dd("Drivers Report");
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		$data['request'] = null;
		// dd($data);
		return view('reports.driveradvance', $data);
	}

	public function driversAdvance_post(Request $request)
	{
		// dd("Post");
		// dd("Drivers Post Report");
		// dd($request->all());
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		if ($request->get('date1') == null)
			$start = Bookings::orderBy('pickup', 'ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Bookings::orderBy('pickup', 'DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d', strtotime($start));
		$end = date('Y-m-d', strtotime($end));



		$bookData = Bookings::meta()
			->where(function ($query) {
				$query->where('bookings_meta.key', '=', 'advance_pay')
					->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
			})->whereBetween(DB::raw('DATE(pickup)'), [$start, $end])->with('vehicle');
		if (!empty($driver_id))
			$data['advance_bookings'] = $bookData->where('driver_id', $driver_id)->orderBy("id", "DESC")->get();
		else
			$data['advance_bookings'] = $bookData->orderBy("id", "DESC")->get();


		// dd($data);

		// $data['total_advance'] = $data['advance_bookings']->where('bookings_meta.key', '=', 'advance_pay')->sum('bookings_meta.value');
		// $data['payable_salary'] = $data['payroll']->sum('payable_salary');
		// $data['advance'] = $data['total_salary'] -$data['payable_salary'];
		// dd($data['advance_bookings']->sum('advance_pay'));

		// Vehicle selected
		if (!empty($driver_id)) {
			$drmodel = VehicleModel::find($driver_id);
			$data['vehicleData'] = !empty($drmodel) ? $drmodel->vehicle : null;
		} else $data['vehicleData'] = null;
		// dd($data);
		$data['total_advance'] = $data['advance_bookings']->sum('advance_pay');
		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['request'] = $request->all();
		// dd($data['advance_bookings']->first());
		return view('reports.driveradvance', $data);
	}

	public function driversAdvance_print(Request $request)
	{
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type', 'D')->pluck('name', 'id');
		if ($request->get('date1') == null)
			$start = Bookings::orderBy('pickup', 'ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Bookings::orderBy('pickup', 'DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d', strtotime($start));
		$end = date('Y-m-d', strtotime($end));



		$bookData = Bookings::meta()
			->where(function ($query) {
				$query->where('bookings_meta.key', '=', 'advance_pay')
					->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
			})->whereBetween(DB::raw('DATE(pickup)'), [$start, $end])->with('vehicle');
		if (!empty($driver_id))
			$data['advance_bookings'] = $bookData->where('driver_id', $driver_id)->orderBy("id", "DESC")->get();
		else
			$data['advance_bookings'] = $bookData->orderBy("id", "DESC")->get();


		// Vehicle selected
		if (!empty($driver_id)) {
			$drmodel = VehicleModel::find($driver_id);
			$data['vehicleData'] = !empty($drmodel) ? $drmodel->vehicle : null;
		} else $data['vehicleData'] = null;
		// dd($data);
		$data['total_advance'] = $data['advance_bookings']->sum('advance_pay');
		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['request'] = $request->all();
		$data['driver_name'] = !empty($driver_id) ? User::find($driver_id)->name : null;
		$data['from_date'] = Helper::getCanonicalDate($start, 'default');
		$data['to_date'] = Helper::getCanonicalDate($end, 'default');
		// dd($data);
		return view('reports.print-driveradvance', $data);
	}
	public function statement()
	{
		$data['request'] = null;
		$data['from_date'] = null;
		$data['to_date'] = null;
		return view('reports.statement', $data);
	}

	public function statement_post(Request $request)
	{
		// dd($request->all());
		$incomeExpense = IncomeExpense::orderBy("id", "ASC")->get();
		// $transaction = Transaction::get();

		foreach ($incomeExpense as $key => $tr) {
			// dd($tr,$tr->transaction_id,$tr->transaction);

			$trash = $tr->transaction;
			// dd($trash);
			if (!$trash == null) {
				//Booking,PartsInvoice,Fuel (Break and Continue)
				if (($trash->param_id == 18 || $trash->param_id == 20 || $trash->param_id == 26 || $trash->param_id == 28) && $tr->amount == 0) { //check for advance_for=21
					$incomeExpense->forget($key);
					continue;
				}
				$transaction_data = Helper::getActualTransactionDate($trash->from_id, $trash->param_id);
				// if ($trash->id == 6575) dd($transaction_data);
				// if ($transaction_data->date == "0000-00-00") dd($trash->from_id, $trash->param_id);
				$tr->dateof = $transaction_data->date;

				// if ($trash->param_id == 18) { //bookings
				// 	if ($tr->amount == 0 || $tr->amount == null) {
				// 		$shalom = Bookings::where('id', $trash->from_id);
				// 		$tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
				// 	} else { //for driver advance
				// 		$tr->dateof = !empty($tr->date) ? $tr->date . " 00:00:00" : null;
				// 	}
				// } else if ($trash->param_id == 19) { //payroll
				// 	$shalom = Payroll::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 20) { //fuel
				// 	if ($tr->amount == 0 || $tr->amount == null) {
				// 		$shalom = FuelModel::where('id', $trash->from_id);
				// 		$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// 	} else {
				// 		$tr->dateof = !empty($tr->date) ? $tr->date : null;
				// 	}
				// } else if ($trash->param_id == 25) { //salary advance
				// 	$shalom = DailyAdvance::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 26) { //parts invoice
				// 	if ($tr->amount == 0 || $tr->amount == null) {
				// 		$shalom = PartsInvoice::where('id', $trash->from_id);
				// 		$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				// 	} else {
				// 		$tr->dateof = !empty($tr->date) ? $tr->date : null;
				// 	}
				// } else if ($trash->param_id == 27) { //advance driver refund
				// 	// $shalom = AdvanceDriver::where('id',$trash->from_id);
				// 	// $tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
				// 	$tr->dateof = $trash->created_at;
				// } else if ($trash->param_id == 28) { //work order
				// 	$shalom = WorkOrders::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				// } else if ($trash->param_id == 29) { //starting amount
				// 	$shalom = BankAccount::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->updated_at : null;
				// } else if ($trash->param_id == 30) { //deposit
				// 	$shalom = BankTransaction::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 31) { //revised
				// 	$shalom = BankTransaction::where(['id' => $trash->from_id, 'from_id' => !null]);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 32) { //driver liability
				// 	$shalom = DailyAdvance::where(['id' => $trash->from_id, 'from_id' => !null]);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 35) { //document renew
				// 	$shalom = VehicleDocs::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				// } else if ($trash->param_id == 43) { //other advance
				// 	$shalom = OtherAdvance::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 44) { //advance refund
				// 	$shalom = OtherAdjust::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 49) { //down payment
				// 	$shalom = PurchaseInfo::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->purchase_date : null;
				// } else if ($trash->param_id == 50) { //emi date
				// 	$shalom = EmiModel::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// }
				$tr->type = $trash->type;
				// if ($trash->id == 6575) dd($tr);
			}
		}
		// dd($transaction->reverse()->toArray());
		// $filtered = $transaction;
		$filtered = $incomeExpense->where('dateof', '!=', null)->sortBy('dateof')->flatten();
		// $filtered1 = $incomeExpense->where('dateof','=',null)->flatten();
		// dd($incomeExpense, $filtered);
		// dd($filtered->first());
		if ($request->get('date1') == null)
			$start = !empty($filtered) ? $filtered->first()->dateof : null;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = !empty($filtered) ? $filtered->reverse()->first()->dateof : null;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d', strtotime($start));
		$end = date('Y-m-d', strtotime($end));
		// dd($filtered);
		// dd($filtered->get(count($filtered)-2));
		// dd($filtered->first(),$filtered->reverse()->first());
		// dd($start,$end);
		//Opening Balance and closing balance
		$openingCredit = $filtered->where('dateof', '<', $start)->where('type', 23)->sum('amount'); //DOUBT X
		$openingDebit = $filtered->where('dateof', '<', $start)->where('type', 24)->sum('amount');
		$openingBalance = $openingCredit - $openingDebit;
		// dd($openingCredit,$openingDebit,$openingBalance);

		$closingCredit = $filtered->whereBetween('dateof', [$start, $end])->where('type', 23)->sum('amount');
		$closingDebit = $filtered->whereBetween('dateof', [$start, $end])->where('type', 24)->sum('amount');
		$closingAmount = $closingCredit - $closingDebit;

		if ($openingBalance == 0) {
			$closingBalance = $closingAmount;
		} else {
			$closingBalance = $openingBalance + $closingAmount;
		}

		// dd($openingBalance,$closingCredit,$closingDebit,$closingAmount,$closingBalance); //DOUBT X

		// $filtered = strtotime($start)==strtotime($end) ? $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"]) : $filtered->whereBetween('dateof',[$start,$end]);
		$filtered = $filtered->whereBetween('dateof', [$start, $end]);

		// dd($filtered);
		$data['transactions'] = $filtered->flatten();
		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['from_date'] = date("Y-m-d", strtotime($start));
		$data['to_date'] = date("Y-m-d", strtotime($end));
		$data['openingBalance'] = $openingBalance;
		$data['closingBalance'] = $closingBalance;
		$data['request'] = $request->all();
		// dd($data);
		// dd($data['transactions']->first());
		return view('reports.statement', $data);
	}

	public function statement_print(Request $request)
	{
		// dd($request->all());
		$incomeExpense = IncomeExpense::orderBy("id", "ASC")->get();
		// $transaction = Transaction::get();

		foreach ($incomeExpense as $key => $tr) {
			// dd($tr,$tr->transaction_id,$tr->transaction);

			$trash = $tr->transaction;
			// dd($trash);
			if (!$trash == null) {
				//Booking,PartsInvoice,Fuel (Break and Continue)
				if (($trash->param_id == 18 || $trash->param_id == 20 || $trash->param_id == 26 || $trash->param_id == 28) && $tr->amount == 0) { //check for advance_for=21
					$incomeExpense->forget($key);
					continue;
				}
				$transaction_data = Helper::getActualTransactionDate($trash->from_id, $trash->param_id);
				// if ($trash->id == 6575) dd($transaction_data);
				$tr->dateof = $transaction_data->date;

				// if ($trash->param_id == 18) { //bookings
				// 	if ($tr->amount == 0 || $tr->amount == null) {
				// 		$shalom = Bookings::where('id', $trash->from_id);
				// 		$tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
				// 	} else { //for driver advance
				// 		$tr->dateof = !empty($tr->date) ? $tr->date . " 00:00:00" : null;
				// 	}
				// } else if ($trash->param_id == 19) { //payroll
				// 	$shalom = Payroll::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 20) { //fuel
				// 	if ($tr->amount == 0 || $tr->amount == null) {
				// 		$shalom = FuelModel::where('id', $trash->from_id);
				// 		$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// 	} else {
				// 		$tr->dateof = !empty($tr->date) ? $tr->date : null;
				// 	}
				// } else if ($trash->param_id == 25) { //salary advance
				// 	$shalom = DailyAdvance::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 26) { //parts invoice
				// 	if ($tr->amount == 0 || $tr->amount == null) {
				// 		$shalom = PartsInvoice::where('id', $trash->from_id);
				// 		$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				// 	} else {
				// 		$tr->dateof = !empty($tr->date) ? $tr->date : null;
				// 	}
				// } else if ($trash->param_id == 27) { //advance driver refund
				// 	// $shalom = AdvanceDriver::where('id',$trash->from_id);
				// 	// $tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
				// 	$tr->dateof = $trash->created_at;
				// } else if ($trash->param_id == 28) { //work order
				// 	$shalom = WorkOrders::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				// } else if ($trash->param_id == 29) { //starting amount
				// 	$shalom = BankAccount::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->updated_at : null;
				// } else if ($trash->param_id == 30) { //deposit
				// 	$shalom = BankTransaction::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 31) { //revised
				// 	$shalom = BankTransaction::where(['id' => $trash->from_id, 'from_id' => !null]);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 32) { //driver liability
				// 	$shalom = DailyAdvance::where(['id' => $trash->from_id, 'from_id' => !null]);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 35) { //document renew
				// 	$shalom = VehicleDocs::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				// } else if ($trash->param_id == 43) { //other advance
				// 	$shalom = OtherAdvance::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 44) { //advance refund
				// 	$shalom = OtherAdjust::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// } else if ($trash->param_id == 49) { //down payment
				// 	$shalom = PurchaseInfo::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->purchase_date : null;
				// } else if ($trash->param_id == 50) { //emi date
				// 	$shalom = EmiModel::where('id', $trash->from_id);
				// 	$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				// }
				$tr->type = $trash->type;
				// if ($trash->id == 6575) dd($tr);
			}
		}
		// dd($transaction->reverse()->toArray());
		// $filtered = $transaction;
		$filtered = $incomeExpense->where('dateof', '!=', null)->sortBy('dateof')->flatten();
		// $filtered1 = $incomeExpense->where('dateof','=',null)->flatten();
		// dd($incomeExpense,$filtered,$filtered1);
		if ($request->get('date1') == null)
			$start = !empty($filtered) ? $filtered->first()->dateof : null;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = !empty($filtered) ? $filtered->reverse()->first()->dateof : null;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d', strtotime($start));
		$end = date('Y-m-d', strtotime($end));
		// dd($filtered);
		// dd($filtered->get(count($filtered)-2));
		// dd($filtered->first(),$filtered->reverse()->first());
		// dd($start,$end);
		//Opening Balance and closing balance
		$openingCredit = $filtered->where('dateof', '<', $start)->where('type', 23)->sum('amount'); //DOUBT X
		$openingDebit = $filtered->where('dateof', '<', $start)->where('type', 24)->sum('amount');
		$openingBalance = $openingCredit - $openingDebit;
		// dd($openingCredit,$openingDebit,$openingBalance);

		$closingCredit = $filtered->whereBetween('dateof', [$start, $end])->where('type', 23)->sum('amount');
		$closingDebit = $filtered->whereBetween('dateof', [$start, $end])->where('type', 24)->sum('amount');
		$closingAmount = $closingCredit - $closingDebit;

		if ($openingBalance == 0) {
			$closingBalance = $closingAmount;
		} else {
			$closingBalance = $openingBalance + $closingAmount;
		}

		// dd($openingBalance,$closingCredit,$closingDebit,$closingAmount,$closingBalance); //DOUBT X

		// $filtered = strtotime($start)==strtotime($end) ? $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"]) : $filtered->whereBetween('dateof',[$start,$end]);
		$filtered = $filtered->whereBetween('dateof', [$start, $end]);

		// dd($filtered);
		$data['transactions'] = $filtered->flatten();
		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['from_date'] = date("Y-m-d", strtotime($start));
		$data['to_date'] = date("Y-m-d", strtotime($end));
		$data['openingBalance'] = $openingBalance;
		$data['closingBalance'] = $closingBalance;
		$data['request'] = $request->all();
		$data['closingAmount'] = $closingAmount;
		// dd($data);
		return view('reports.statement-report', $data);
	}

	public function vehicleAdvance()
	{
		// dd('Driver Advance');
		// dd("Drivers Report");
		// $booking_ids = AdvanceDriver::groupBy("booking_id")->pluck("booking_id");
		// $vehicle_ids = Bookings::meta()->where(function($query){
		// 	$query->where('bookings_meta.key', '=', 'advance_pay')
		// 		  ->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
		// })->groupBy('vehicle_id')->pluck('vehicle_id');
		$vehicles = VehicleModel::pluck('license_plate', 'id');
		$heads = Params::where('code', 'AdvanceDriver')->pluck('label', 'id');
		$data['vehicles'] = $vehicles;
		$data['heads'] = $heads;
		$data['request'] = null;
		// dd($data);
		return view('reports.vehicleadvance', $data);
	}

	public function vehicleAdvance_post(Request $request)
	{
		// dd("Post");
		// dd("Drivers Post Report");
		// dd($request->all());
		$vehicle_id = $request->get('vehicle_id');
		$param_id = $request->get('param_id');

		//Start Date and End Date
		if ($request->get('date1') == null)
			$start = Bookings::orderBy('pickup', 'ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Bookings::orderBy('pickup', 'DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = $from_date = date('Y-m-d', strtotime($start));
		$end = $to_date = date('Y-m-d', strtotime($end));
		$start = $start . " 00:00:00";
		$end = $end . " 23:59:59";
		// dd($start,$end);
		$advanceDriver = DB::table('advance_driver')
			->join('bookings', 'advance_driver.booking_id', '=', 'bookings.id')
			->select('advance_driver.*', 'bookings.vehicle_id', DB::raw("SUM(advance_driver.value) as total", 'bookings.pickup', 'bookings.dropoff'))
			->whereBetween('bookings.pickup', [$start, $end])
			->groupBy('advance_driver.param_id', 'bookings.vehicle_id')
			->orderBy('bookings.vehicle_id', 'ASC')
			->orderBy('advance_driver.param_id', 'ASC')
			->get();
		if (!empty($vehicle_id))
			$advanceDriver = $advanceDriver->where('vehicle_id', $vehicle_id);
		if (!empty($param_id))
			$advanceDriver = $advanceDriver->where('param_id', $param_id);

		$advanceDriver = $advanceDriver->toArray();
		// dd($vehicle_id,$advanceDriver);
		$vehicles = VehicleModel::pluck('license_plate', 'id');
		$heads = Params::where('code', 'AdvanceDriver')->pluck('label', 'id');

		$data['advanceDriver'] = AdvanceDriver::hydrate($advanceDriver)->values();
		$data['vehicles'] = $vehicles;
		$data['heads'] = $heads;
		$data['result'] = "";
		// $request->merge(['date1'=>$from_date,'date2'=>$to_date]);
		$data['request'] = $request->all();

		// dd($data['advanceDriver']->first());
		// dd($data['advanceDriver']->toArray());
		return view('reports.vehicleadvance', $data);
	}

	public function vehicleAdvance_print(Request $request)
	{
		$vehicle_id = $request->get('vehicle_id');
		$param_id = $request->get('param_id');

		//Start Date and End Date
		if ($request->get('date1') == null)
			$start = Bookings::orderBy('pickup', 'ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Bookings::orderBy('pickup', 'DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = $from_date = date('Y-m-d', strtotime($start));
		$end = $to_date = date('Y-m-d', strtotime($end));
		$start = $start . " 00:00:00";
		$end = $end . " 23:59:59";
		// dd($start,$end);
		$advanceDriver = DB::table('advance_driver')
			->join('bookings', 'advance_driver.booking_id', '=', 'bookings.id')
			->select('advance_driver.*', 'bookings.vehicle_id', DB::raw("SUM(advance_driver.value) as total", 'bookings.pickup', 'bookings.dropoff'))
			->whereBetween('bookings.pickup', [$start, $end])
			->groupBy('advance_driver.param_id', 'bookings.vehicle_id')
			->orderBy('bookings.vehicle_id', 'ASC')
			->orderBy('advance_driver.param_id', 'ASC')
			->get();
		if (!empty($vehicle_id))
			$advanceDriver = $advanceDriver->where('vehicle_id', $vehicle_id);
		if (!empty($param_id))
			$advanceDriver = $advanceDriver->where('param_id', $param_id);

		$advanceDriver = $advanceDriver->toArray();
		// dd($vehicle_id,$advanceDriver);


		$data['advanceDriver'] = AdvanceDriver::hydrate($advanceDriver)->values();
		$data['vehicle'] = !empty($request->vehicle_id) ? VehicleModel::select(DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('id', $request->vehicle_id)->first() : null;
		$data['heads'] = !empty($request->param_id) ? Params::where('code', 'AdvanceDriver')->where('id', $request->param_id)->first('label') : null;
		$data['from_date'] = Helper::getCanonicalDate($from_date, 'default');
		$data['to_date'] = Helper::getCanonicalDate($to_date, 'default');
		$data['result'] = "";
		$data['request'] = $request->all();

		// dd($data['advanceDriver']->first());
		// dd($data['advanceDriver']->toArray());
		return view('reports.print-vehicleadvance', $data);
	}

	public function vehicleHeadAdvance($arr)
	{
		// dd($arr);
		$arr = explode(",", $arr);
		if (count($arr) > 0 && $arr[0] != '' && $arr[1] != '') {

			$vehicle_id = $arr[0];
			$param_id = $arr[1];

			$from_date = $arr[2];
			$to_date = $arr[3];
			$from_date = empty($from_date) ? date("Y-m-d", strtotime(Bookings::orderBy('pickup', 'asc')->take(1)->first('pickup')->pickup)) : $from_date;
			$to_date = empty($to_date) ? date("Y-m-d", strtotime(Bookings::orderBy('pickup', 'desc')->take(1)->first('pickup')->pickup)) : $to_date;

			$from_date = $from_date . " 00:00:00";
			$to_date = $to_date . " 23:59:59";

			if (!empty($vehicle_id))
				$booking_ids = Bookings::whereBetween('pickup', [$from_date, $to_date])->where('vehicle_id', $vehicle_id)->groupBy('id')->pluck('id');
			else
				$booking_ids = Bookings::whereBetween('pickup', [$from_date, $to_date])->groupBy('id')->pluck('id');


			if (!empty($param_id))
				$advanceDriver = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', $param_id);
			else
				$advanceDriver = AdvanceDriver::whereIn('booking_id', $booking_ids);



			$index['advanceDriver'] = $advanceDriver->get();
			$index['vehicle'] = !empty($vehicle_id) ? VehicleModel::find($vehicle_id) : null;
			$index['param'] = Params::find($param_id);
			$index['from_date'] = $from_date;
			$index['to_date'] = $to_date;
			// dd($index);
			return view('reports.vehicleheadadvance', $index);
		} else {
			dd($arr);
		}
	}

	public function vehicleHeadAdvance_print(Request $request)
	{
		// dd($request->all());
		$vehicle_id = $request->vehicle_id;
		$param_id = $request->param_id;

		$from_date = $request->from_date;
		$to_date = $request->to_date;

		if (!empty($vehicle_id))
			$booking_ids = Bookings::whereBetween('pickup', [$from_date, $to_date])->where('vehicle_id', $vehicle_id)->groupBy('id')->pluck('id');
		else
			$booking_ids = Bookings::whereBetween('pickup', [$from_date, $to_date])->groupBy('id')->pluck('id');


		if (!empty($param_id))
			$advanceDriver = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', $param_id);
		else
			$advanceDriver = AdvanceDriver::whereIn('booking_id', $booking_ids);



		$index['advanceDriver'] = $advanceDriver->get();
		$index['vehicle'] = !empty($vehicle_id) ? VehicleModel::find($vehicle_id) : null;
		$index['param'] = Params::find($param_id);
		$index['from_date'] = $from_date;
		$index['to_date'] = $to_date;
		// dd($index);
		return view('reports.vehicleheadadvance_print', $index);
	}


	public function salaryReport()
	{
		$data['drivers'] = User::getDrivers()->orderBy('name', 'ASC')->pluck('name', 'id');
		$data['months'] = Helper::getMonths();
		$from_year = Payroll::groupBy('for_year')->orderBy('for_year', 'ASC')->first()->for_year;
		$to_year = Payroll::groupBy('for_year')->orderBy('for_year', 'DESC')->first()->for_year;
		$data['years'] = Helper::getYears(['from' => $from_year, 'to' => $to_year + 1]);
		$data['request'] = null;
		// dd($data);
		return view('reports.salary-report', $data);
	}

	public function salaryReport_post(Request $request)
	{
		// dd($request->all());
		$driver_ids = $request->driver_id;
		if ($driver_ids == null || empty($driver_ids) || in_array(null, $driver_ids)) {
			// $driver_ids = User::getDrivers()->orderBy('name', 'ASC')->pluck('id');
			$driver_ids = User::haveSalary()->getDrivers()->orderBy('name', 'ASC')->pluck('id');
		}

		$month = $request->get('months') < 10 ? "0" . $request->get('months') : $request->get('months');
		$year = $request->get('years');

		$search_date = "$year-$month-01";
		$search_ym = "$year-$month";
		// dd($search_ym);
		$arrayList = [];
		foreach ($driver_ids as $did) {
			$payroll = Payroll::where('for_date', $search_date)->where('user_id', $did);
			// if($payroll->exists()) dd($payroll->first()->toArray());

			//Working Days
			$present = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->where('is_present', 1)->get();
			$halfLeave = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->whereIn('is_present', [3, 4])->get();

			$presentDays = $present->count() + ($halfLeave->count() * .5);
			$totalMonthDays = date('t', strtotime($search_date));
			$absentDays = $totalMonthDays - $presentDays;

			//Calculating advances
			$salary_advance = DailyAdvance::where('date', 'LIKE', "%$search_ym%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->sum('amount');
			// dd(Bookings::where('driver_id',$did)->get());
			$booking_ids = Bookings::where('driver_id', $did)->where(function ($query) {
				$query->where('payroll_check', '!=', 1)
					->orWhereRaw('bookings.payroll_check IS NULL');
			})->where('pickup', 'LIKE', "%$search_ym%")->pluck('id')->toArray();
			// dd($booking_ids);
			if (!empty($booking_ids)) {
				// dd($did,$booking_ids);
				$bookingAdvance = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', 7)->sum('value');
				// dd($bookingAdvance);
			} else $bookingAdvance = 0;
			// if($did==10) dd($totalMonthDays,$absentDays,$presentDays);
			$userData =  User::where('id', $did)->first();
			$gross_salary = $userData->salary;
			$user_vehicle =  !empty($userData->driver_vehicle->vehicle) ? $userData->driver_vehicle->vehicle->license_plate : "-";
			$payable_salary = $gross_salary - ($salary_advance + $bookingAdvance);

			if ($totalMonthDays == $absentDays && $presentDays == 0) {
				$payable_salary = 0;
				$deduct_amount = 0;
			} else {
				$perday = bcdiv($gross_salary / $totalMonthDays, 1, 2);
				$deduct_amount = bcdiv($absentDays * $perday, 1, 2);
				$payable_salary = $payable_salary - $deduct_amount;
			}
			// if($did==10) dd($payable_salary,$deduct_amount);
			if ($payroll->exists()) {
				$paydata = $payroll->first();
				$paydata->is_payroll = true;
				$paydata->days_present = $presentDays;
				$paydata->days_absent = $absentDays;
				$paydata->bookingAdvance = $bookingAdvance;
				$paydata->gross_salary = $gross_salary;
				$paydata->salary_advance = $salary_advance;
				$paydata->deduct_amount = $deduct_amount;
				$arrayList[] = $paydata;
				// if ($did == 10) dd('exists');
			} else {
				//Setting Random ids
				$primaryID =  rand(1000, 100000);
				//Payabl Salary

				$newArray = [
					"id" => $primaryID,
					"user_id" => $did,
					"driver" =>  User::find($did)->name,
					"vehicle" =>  $user_vehicle,
					"salary" => $gross_salary,
					"date" => $search_date,
					"for_date" => $search_date,
					"payable_salary" => $payable_salary, //find out
					"for_month" => date('m', strtotime($search_date)),
					"for_year" => date('Y', strtotime($search_date)),
					"is_payroll" => false,
					"days_present" => $presentDays,
					"days_absent" => $absentDays,
					"bookingAdvance" => $bookingAdvance,
					"gross_salary" => $gross_salary,
					"salary_advance" => $salary_advance,
					"deduct_amount" => $deduct_amount,

				];
				// if($did==10)
				// 	dd($newArray);
				$arrayList[] = Helper::toCollection($newArray);
			}
		}
		$finalList = collect($arrayList)->values();
		// dd(Payroll::hydrate($arrayList));
		$data['salaries'] = $finalList;
		$data['drivers'] = User::getDrivers()->orderBy('name', 'ASC')->pluck('name', 'id');
		$data['months'] = Helper::getMonths();
		$from_year = Payroll::groupBy('for_year')->orderBy('for_year', 'ASC')->first()->for_year;
		$to_year = Payroll::groupBy('for_year')->orderBy('for_year', 'DESC')->first()->for_year;
		$data['years'] = Helper::getYears(['from' => $from_year, 'to' => $to_year + 1]);
		$data['result'] = "";

		if (!$request->has('driver_id'))	$request->merge(['driver_id' => null]);
		$data['request'] = $request->all();


		// dd($data);
		// dd(request()->segments(count($request->segments())));

		return view('reports.salary-report', $data);
	}

	public function salaryReport_print(Request $request)
	{
		$driver_ids = $request->driver_id;
		if ($driver_ids == null || empty($driver_ids) || in_array(null, $driver_ids)) {
			$driver_ids = User::getDrivers()->orderBy('name', 'ASC')->pluck('id');
		}

		$month = $request->get('months') < 10 ? "0" . $request->get('months') : $request->get('months');
		$year = $request->get('years');

		$search_date = "$year-$month-01";
		$search_ym = "$year-$month";
		// dd($search_ym);
		$arrayList = [];
		foreach ($driver_ids as $did) {
			$payroll = Payroll::where('for_date', $search_date)->where('user_id', $did);
			// if($payroll->exists()) dd($payroll->first()->toArray());

			//Working Days
			$present = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->where('is_present', 1)->get();
			$halfLeave = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->whereIn('is_present', [3, 4])->get();

			$presentDays = $present->count() + ($halfLeave->count() * .5);
			$totalMonthDays = date('t', strtotime($search_date));
			$absentDays = $totalMonthDays - $presentDays;

			//Calculating advances
			$salary_advance = DailyAdvance::where('date', 'LIKE', "%$search_ym%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->sum('amount');
			// dd(Bookings::where('driver_id',$did)->get());
			$booking_ids = Bookings::where('driver_id', $did)->where(function ($query) {
				$query->where('payroll_check', '!=', 1)
					->orWhereRaw('bookings.payroll_check IS NULL');
			})->where('pickup', 'LIKE', "%$search_ym%")->pluck('id')->toArray();
			// dd($booking_ids);
			if (!empty($booking_ids)) {
				// dd($did,$booking_ids);
				$bookingAdvance = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', 7)->sum('value');
				// dd($bookingAdvance);
			} else $bookingAdvance = 0;

			$userData =  User::where('id', $did)->first();
			$gross_salary = $userData->salary;
			$user_vehicle =  !empty($userData->driver_vehicle->vehicle) ? $userData->driver_vehicle->vehicle->license_plate : "-";
			$payable_salary = $gross_salary - ($salary_advance + $bookingAdvance);

			if ($totalMonthDays == $absentDays && $presentDays == 0) {
				$payable_salary = 0;
				$deduct_amount = 0;
			} else {
				$perday = bcdiv($gross_salary / $totalMonthDays, 1, 2);
				$deduct_amount = bcdiv($absentDays * $perday, 1, 2);
				$payable_salary = $payable_salary - $deduct_amount;
			}
			// dd($driver_vehicle);
			if ($payroll->exists()) {
				$paydata = $payroll->first();
				$paydata->is_payroll = true;
				$paydata->days_present = $presentDays;
				$paydata->days_absent = $absentDays;
				$paydata->bookingAdvance = $bookingAdvance;
				$paydata->gross_salary = $gross_salary;
				$paydata->salary_advance = $salary_advance;
				$paydata->deduct_amount = $deduct_amount;
				$arrayList[] = $paydata;
			} else {
				//Setting Random ids
				$primaryID =  rand(1000, 100000);
				//Payabl Salary

				$newArray = [
					"id" => $primaryID,
					"user_id" => $did,
					"driver" =>  User::find($did)->name,
					"vehicle" =>  $user_vehicle,
					"salary" => $gross_salary,
					"date" => $search_date,
					"for_date" => $search_date,
					"payable_salary" => $payable_salary, //find out
					"for_month" => date('m', strtotime($search_date)),
					"for_year" => date('Y', strtotime($search_date)),
					"is_payroll" => false,
					"days_present" => $presentDays,
					"days_absent" => $absentDays,
					"bookingAdvance" => $bookingAdvance,
					"gross_salary" => $gross_salary,
					"salary_advance" => $salary_advance,
					"deduct_amount" => $deduct_amount,

				];
				// if($did==21)
				// 	dd($newArray);
				$arrayList[] = Helper::toCollection($newArray);
			}
		}
		$finalList = collect($arrayList)->values();
		// dd(Payroll::hydrate($arrayList));
		$data['salaries'] = $finalList;
		$data['request'] = $request->all();
		$data['result'] = "";

		if (!$request->has('driver_id'))	$request->merge(['driver_id' => null]);
		// $data['request'] = $request->all();
		$dateFor = $request['years'] . '-' . $request['months'] . "-01";
		$data['date1'] = date("m-Y", strtotime($dateFor));
		$data['date2'] = date("F-Y", strtotime($dateFor));



		// dd($data);
		// dd(request()->segments(count($request->segments())));

		return view('reports.print_salary-report', $data);
	}
	public function salaryProcessing()
	{
		$data['drivers'] = User::getDrivers()->orderBy('name', 'ASC')->pluck('name', 'id');
		$data['months'] = Helper::getMonths();
		$from_year = Payroll::groupBy('for_year')->orderBy('for_year', 'ASC')->first()->for_year;
		$to_year = Payroll::groupBy('for_year')->orderBy('for_year', 'DESC')->first()->for_year;
		$data['years'] = Helper::getYears(['from' => $from_year, 'to' => $to_year + 1]);
		$data['request'] = null;
		// dd($data);
		return view('reports.salary-processing', $data);
	}

	public function salaryProcessing_post(Request $request)
	{
		// dd($request->all());
		$driver_ids = $request->driver_id;
		if ($driver_ids == null || empty($driver_ids) || in_array(null, $driver_ids)) {
			$driver_ids = User::getDrivers()->orderBy('name', 'ASC')->pluck('id');
		}

		$month = $request->get('months') < 10 ? "0" . $request->get('months') : $request->get('months');
		$year = $request->get('years');

		$search_date = "$year-$month-01";
		$search_ym = "$year-$month";
		// dd($search_ym);
		$arrayList = [];
		foreach ($driver_ids as $did) {
			$payroll = Payroll::where('for_date', $search_date)->where('user_id', $did);
			// if($payroll->exists()) dd($payroll->first()->toArray());

			//Working Days
			$present = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->where('is_present', 1)->get();
			$halfLeave = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->whereIn('is_present', [3, 4])->get();

			$presentDays = $present->count() + ($halfLeave->count() * .5);
			$totalMonthDays = date('t', strtotime($search_date));
			$absentDays = $totalMonthDays - $presentDays;

			//Calculating advances
			$salary_advance = DailyAdvance::where('date', 'LIKE', "%$search_ym%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->sum('amount');
			// dd(Bookings::where('driver_id',$did)->get());
			$booking_ids = Bookings::where('driver_id', $did)->where(function ($query) {
				$query->where('payroll_check', '!=', 1)
					->orWhereRaw('bookings.payroll_check IS NULL');
			})->where('pickup', 'LIKE', "%$search_ym%")->pluck('id')->toArray();
			// dd($booking_ids);
			if (!empty($booking_ids)) {
				// dd($did,$booking_ids);
				$bookingAdvance = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', 7)->sum('value');
				// dd($bookingAdvance);
			} else $bookingAdvance = 0;

			$userData =  User::where('id', $did)->first();
			$gross_salary = $userData->salary;
			$user_vehicle =  !empty($userData->driver_vehicle->vehicle) ? $userData->driver_vehicle->vehicle->license_plate : "-";
			$payable_salary = $gross_salary - ($salary_advance + $bookingAdvance);

			if ($totalMonthDays == $absentDays && $presentDays == 0) {
				$payable_salary = 0;
				$deduct_amount = 0;
			} else {
				$perday = bcdiv($gross_salary / $totalMonthDays, 1, 2);
				$deduct_amount = bcdiv($absentDays * $perday, 1, 2);
				$payable_salary = $payable_salary - $deduct_amount;
			}
			// if($did==66) dd($payable_salary,$deduct_amount);
			if ($payroll->exists()) {
				// dd(1);
				$paydata = $payroll->first();
				$paydata->is_payroll = true;
				// $paydata->days_present = $presentDays;
				// $paydata->days_absent = $absentDays;
				// $paydata->bookingAdvance = $bookingAdvance;
				$paydata->gross_salary = $gross_salary;
				// $paydata->salary_advance = $salary_advance;
				// $paydata->deduct_amount = $deduct_amount;
				$arrayList[] = $paydata;
				// if($did==66) dd(12);
			} else {
				//Setting Random ids
				$primaryID =  rand(1000, 100000);
				//Payabl Salary

				$newArray = [
					"id" => $primaryID,
					"user_id" => $did,
					"driver" =>  User::find($did)->name,
					"vehicle" =>  $user_vehicle,
					"salary" => $gross_salary,
					"date" => $search_date,
					"for_date" => $search_date,
					"payable_salary" => $payable_salary, //find out
					"for_month" => date('m', strtotime($search_date)),
					"for_year" => date('Y', strtotime($search_date)),
					"is_payroll" => false,
					"bank" => $userData->bank,
					"account_no" => $userData->account_no,
					// "days_present" => $presentDays,
					// "days_absent" => $absentDays,
					// "bookingAdvance" => $bookingAdvance,
					"gross_salary" => $gross_salary,
					// "salary_advance" => $salary_advance,
					// "deduct_amount" => $deduct_amount,

				];
				// if($did==66)
				// 	dd($newArray);
				$arrayList[] = Helper::toCollection($newArray);
			}
		}
		$finalList = collect($arrayList)->values();
		// dd(Payroll::hydrate($arrayList));
		$data['salaries'] = $finalList;
		$data['drivers'] = User::getDrivers()->orderBy('name', 'ASC')->pluck('name', 'id');
		$data['months'] = Helper::getMonths();
		$from_year = Payroll::groupBy('for_year')->orderBy('for_year', 'ASC')->first()->for_year;
		$to_year = Payroll::groupBy('for_year')->orderBy('for_year', 'DESC')->first()->for_year;
		$data['years'] = Helper::getYears(['from' => $from_year, 'to' => $to_year + 1]);
		$data['result'] = "";

		if (!$request->has('driver_id'))	$request->merge(['driver_id' => null]);
		$data['request'] = $request->all();


		// dd($data);
		// dd(request()->segments(count($request->segments())));

		return view('reports.salary-processing', $data);
	}

	public function salaryProcessing_print(Request $request)
	{
		$driver_ids = $request->driver_id;
		if ($driver_ids == null || empty($driver_ids) || in_array(null, $driver_ids)) {
			$driver_ids = User::getDrivers()->orderBy('name', 'ASC')->pluck('id');
		}

		$month = $request->get('months') < 10 ? "0" . $request->get('months') : $request->get('months');
		$year = $request->get('years');

		$search_date = "$year-$month-01";
		$search_ym = "$year-$month";
		// dd($search_ym);
		$arrayList = [];
		foreach ($driver_ids as $did) {
			$payroll = Payroll::where('for_date', $search_date)->where('user_id', $did);
			// if($payroll->exists()) dd($payroll->first()->toArray());

			//Working Days
			$present = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->where('is_present', 1)->get();
			$halfLeave = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->whereIn('is_present', [3, 4])->get();

			$presentDays = $present->count() + ($halfLeave->count() * .5);
			$totalMonthDays = date('t', strtotime($search_date));
			$absentDays = $totalMonthDays - $presentDays;

			//Calculating advances
			$salary_advance = DailyAdvance::where('date', 'LIKE', "%$search_ym%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->sum('amount');
			// dd(Bookings::where('driver_id',$did)->get());
			$booking_ids = Bookings::where('driver_id', $did)->where(function ($query) {
				$query->where('payroll_check', '!=', 1)
					->orWhereRaw('bookings.payroll_check IS NULL');
			})->where('pickup', 'LIKE', "%$search_ym%")->pluck('id')->toArray();
			// dd($booking_ids);
			if (!empty($booking_ids)) {
				// dd($did,$booking_ids);
				$bookingAdvance = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', 7)->sum('value');
				// dd($bookingAdvance);
			} else $bookingAdvance = 0;

			$userData =  User::where('id', $did)->first();
			$gross_salary = $userData->salary;
			$user_vehicle =  !empty($userData->driver_vehicle->vehicle) ? $userData->driver_vehicle->vehicle->license_plate : "-";
			$payable_salary = $gross_salary - ($salary_advance + $bookingAdvance);

			if ($totalMonthDays == $absentDays && $presentDays == 0) {
				$payable_salary = 0;
				$deduct_amount = 0;
			} else {
				$perday = bcdiv($gross_salary / $totalMonthDays, 1, 2);
				$deduct_amount = bcdiv($absentDays * $perday, 1, 2);
				$payable_salary = $payable_salary - $deduct_amount;
			}
			// dd($driver_vehicle);
			if ($payroll->exists()) {
				$paydata = $payroll->first();
				$paydata->is_payroll = true;
				// $paydata->days_present = $presentDays;
				// $paydata->days_absent = $absentDays;
				// $paydata->bookingAdvance = $bookingAdvance;
				$paydata->gross_salary = $gross_salary;
				// $paydata->salary_advance = $salary_advance;
				// $paydata->deduct_amount = $deduct_amount;
				$arrayList[] = $paydata;
			} else {
				//Setting Random ids
				$primaryID =  rand(1000, 100000);
				//Payabl Salary

				$newArray = [
					"id" => $primaryID,
					"user_id" => $did,
					"driver" =>  User::find($did)->name,
					"vehicle" =>  $user_vehicle,
					"salary" => $gross_salary,
					"date" => $search_date,
					"for_date" => $search_date,
					"payable_salary" => $payable_salary, //find out
					"for_month" => date('m', strtotime($search_date)),
					"for_year" => date('Y', strtotime($search_date)),
					"is_payroll" => false,
					// "days_present" => $presentDays,
					// "days_absent" => $absentDays,
					// "bookingAdvance" => $bookingAdvance,
					"gross_salary" => $gross_salary,
					// "salary_advance" => $salary_advance,
					// "deduct_amount" => $deduct_amount,
					"bank" => $userData->bank,
					"account_no" => $userData->account_no,

				];
				// if($did==21)
				// 	dd($newArray);
				$arrayList[] = Helper::toCollection($newArray);
			}
		}
		$finalList = collect($arrayList)->values();
		// dd(Payroll::hydrate($arrayList));
		$data['salaries'] = $finalList;
		$data['request'] = $request->all();
		$data['result'] = "";

		if (!$request->has('driver_id'))	$request->merge(['driver_id' => null]);
		// $data['request'] = $request->all();
		$dateFor = $request['years'] . '-' . $request['months'] . "-01";
		$data['date1'] = date("m-Y", strtotime($dateFor));
		$data['date2'] = date("F-Y", strtotime($dateFor));



		// dd($data);
		// dd(request()->segments(count($request->segments())));

		return view('reports.print_salary-processing', $data);
	}
	public function globalSearch()
	{
		$data['params'] = Params::whereIn('id', [18, 20])->pluck('label', 'id')->toArray();
		$data['vehicles'] = VehicleModel::pluck('license_plate', 'id')->toArray();
		$data['request'] = null;
		$data['param_post'] = null;
		// dd($data);
		return view('reports.global_search.global-search', $data);
	}

	public function globalSearch_post(Request $request)
	{
		// dd($request->all());
		$param_id = $request->param_id;
		$vehicles = $request->vehicle_no;
		$from_date = !empty($request->from_date) ? date("Y-m-d", strtotime($request->from_date)) : null;
		$to_date = !empty($request->to_date) ? date("Y-m-d", strtotime($request->to_date)) : null;

		//Vehicles 
		if (in_array(null, $vehicles))
			$vehicles = VehicleModel::withTrashed()->pluck('id')->toArray();

		//Getting from - to date
		if ($param_id == 18) {
			$from_date = empty($from_date) ? date("Y-m-d", strtotime(Bookings::orderBy('pickup', 'asc')->take(1)->first('pickup')->pickup)) : $from_date;
			$to_date = empty($to_date) ? date("Y-m-d", strtotime(Bookings::orderBy('pickup', 'desc')->take(1)->first('pickup')->pickup)) : $to_date;
		} elseif ($param_id == 20) {
			$from_date = empty($from_date) ? date("Y-m-d", strtotime(FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date)) : $from_date;
			$to_date = empty($to_date) ? date("Y-m-d", strtotime(FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date)) : $to_date;
		}

		if ($param_id == 18) {
			$collection = Bookings::whereBetween('pickup', [$from_date, $to_date])->whereIn('vehicle_id', $vehicles)->orderBy('pickup', 'DESC')->get();
			foreach ($collection as $b) {
				$transData = Transaction::where(['from_id' => $b->id, 'param_id' => 18])->where(function ($query) {
					return $query->where('advance_for', null)
						->orWhere('advance_for', '!=', 21);
				});
				$transa = $transData->exists() ? $transData->first() : null;
				$b->transid = !empty($transa->id) ? $transa->id : null;
				$b->invoice_id = !empty($transa->transaction_id) ? $transa->transaction_id : null;
				$b->inc_rows = !empty($transa->id) ? IncomeExpense::where('transaction_id', $transa->id)->count() : 0;
			}
		} elseif ($param_id == 20) {
			$collection = FuelModel::whereBetween('date', [$from_date, $to_date])->whereIn('vehicle_id', $vehicles)->get();
		} else {
			dd($request->all());
		}

		$data['params'] = Params::whereIn('id', [18, 20])->pluck('label', 'id')->toArray();
		$data['vehicles'] = VehicleModel::pluck('license_plate', 'id')->toArray();
		$data['collection'] = $collection;
		$data['request'] = $request->all();
		$data['result'] = "";
		$data['param_post'] = $param_id;
		// dd($data);
		return view('reports.global_search.global-search', $data);
	}

	public function globalSearch_print(Request $request) // not configured
	{
		$driver_ids = $request->driver_id;
		if ($driver_ids == null || empty($driver_ids) || in_array(null, $driver_ids)) {
			$driver_ids = User::getDrivers()->orderBy('name', 'ASC')->pluck('id');
		}

		$month = $request->get('months') < 10 ? "0" . $request->get('months') : $request->get('months');
		$year = $request->get('years');

		$search_date = "$year-$month-01";
		$search_ym = "$year-$month";
		// dd($search_ym);
		$arrayList = [];
		foreach ($driver_ids as $did) {
			$payroll = Payroll::where('for_date', $search_date)->where('user_id', $did);
			// if($payroll->exists()) dd($payroll->first()->toArray());

			//Working Days
			$present = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->where('is_present', 1)->get();
			$halfLeave = Leave::where('driver_id', $did)
				->where('date', 'LIKE', "%$search_ym%")
				->whereIn('is_present', [3, 4])->get();

			$presentDays = $present->count() + ($halfLeave->count() * .5);
			$totalMonthDays = date('t', strtotime($search_date));
			$absentDays = $totalMonthDays - $presentDays;

			//Calculating advances
			$salary_advance = DailyAdvance::where('date', 'LIKE', "%$search_ym%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->sum('amount');
			// dd(Bookings::where('driver_id',$did)->get());
			$booking_ids = Bookings::where('driver_id', $did)->where(function ($query) {
				$query->where('payroll_check', '!=', 1)
					->orWhereRaw('bookings.payroll_check IS NULL');
			})->where('pickup', 'LIKE', "%$search_ym%")->pluck('id')->toArray();
			// dd($booking_ids);
			if (!empty($booking_ids)) {
				// dd($did,$booking_ids);
				$bookingAdvance = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', 7)->sum('value');
				// dd($bookingAdvance);
			} else $bookingAdvance = 0;

			$userData =  User::where('id', $did)->first();
			$gross_salary = $userData->salary;
			$user_vehicle =  !empty($userData->driver_vehicle->vehicle) ? $userData->driver_vehicle->vehicle->license_plate : "-";
			$payable_salary = $gross_salary - ($salary_advance + $bookingAdvance);

			if ($totalMonthDays == $absentDays && $presentDays == 0) {
				$payable_salary = 0;
				$deduct_amount = 0;
			} else {
				$perday = bcdiv($gross_salary / $totalMonthDays, 1, 2);
				$deduct_amount = bcdiv($absentDays * $perday, 1, 2);
				$payable_salary = $payable_salary - $deduct_amount;
			}
			// dd($driver_vehicle);
			if ($payroll->exists()) {
				$paydata = $payroll->first();
				$paydata->is_payroll = true;
				// $paydata->days_present = $presentDays;
				// $paydata->days_absent = $absentDays;
				// $paydata->bookingAdvance = $bookingAdvance;
				$paydata->gross_salary = $gross_salary;
				// $paydata->salary_advance = $salary_advance;
				// $paydata->deduct_amount = $deduct_amount;
				$arrayList[] = $paydata;
			} else {
				//Setting Random ids
				$primaryID =  rand(1000, 100000);
				//Payabl Salary

				$newArray = [
					"id" => $primaryID,
					"user_id" => $did,
					"driver" =>  User::find($did)->name,
					"vehicle" =>  $user_vehicle,
					"salary" => $gross_salary,
					"date" => $search_date,
					"for_date" => $search_date,
					"payable_salary" => $payable_salary, //find out
					"for_month" => date('m', strtotime($search_date)),
					"for_year" => date('Y', strtotime($search_date)),
					"is_payroll" => false,
					// "days_present" => $presentDays,
					// "days_absent" => $absentDays,
					// "bookingAdvance" => $bookingAdvance,
					"gross_salary" => $gross_salary,
					// "salary_advance" => $salary_advance,
					// "deduct_amount" => $deduct_amount,
					"bank" => $userData->bank,
					"account_no" => $userData->account_no,

				];
				// if($did==21)
				// 	dd($newArray);
				$arrayList[] = Helper::toCollection($newArray);
			}
		}
		$finalList = collect($arrayList)->values();
		// dd(Payroll::hydrate($arrayList));
		$data['salaries'] = $finalList;
		$data['request'] = $request->all();
		$data['result'] = "";

		if (!$request->has('driver_id'))	$request->merge(['driver_id' => null]);
		// $data['request'] = $request->all();
		$dateFor = $request['years'] . '-' . $request['months'] . "-01";
		$data['date1'] = date("m-Y", strtotime($dateFor));
		$data['date2'] = date("F-Y", strtotime($dateFor));



		// dd($data);
		// dd(request()->segments(count($request->segments())));

		return view('reports.print_salary-processing', $data);
	}

	public function vehicleEmi()
	{
		// dd(VehicleModel::whereHas('vehicles')->pluck('license_plate', 'id')->toArray());
		$data['drivers'] = User::whereHas('drivers')->pluck('name', 'id')->toArray();
		$data['vehicles'] = VehicleModel::whereHas('vehicles')->pluck('license_plate', 'id')->toArray();
		$data['banks'] = BankAccount::select("id", DB::raw("CONCAT(bank,'(',account_no,')') as name"))->whereHas('emi_banks')->pluck('name', 'id')->toArray();
		$data['request'] = null;
		// dd($data);
		return view('reports.vehicle-emi', $data);
	}

	public function vehicleEmi_post(Request $request)
	{
		// dd($request->all());
		$search_type = $request->search_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;

		// dd($search_type);

		//where[] Array construction
		$where = array();
		if (!empty($request->vehicle_id)) $where['vehicle_id'] = $request->vehicle_id;
		if (!empty($request->driver_id)) $where['driver_id'] = $request->driver_id;
		if (!empty($request->bank_id)) $where['bank_id'] = $request->driver_id;

		//getting from and to date
		if (empty($request->from_date))
			$from_date = EmiModel::orderBy($search_type, 'ASC')->take(1)->first()->$search_type;

		if (empty($request->to_date))
			$to_date = EmiModel::orderBy($search_type, 'DESC')->take(1)->first()->$search_type;

		$emiData = !empty($where) ? EmiModel::where($where)->descending() : EmiModel::descending();
		// dd($emiData->get());
		if (!empty($request->reference_no))
			$emiData = $emiData->whereBetween($search_type, [$from_date, $to_date])->where('reference_no', 'LIKE', "%" . $request->reference_no . "%");
		else
			$emiData = $emiData->whereBetween($search_type, [$from_date, $to_date]);

		// dd($emiData->get());
		// dd($from_date, $to_date);


		$data['emiData'] = $emiData->get();
		$data['drivers'] = User::whereHas('drivers')->pluck('name', 'id')->toArray();
		$data['vehicles'] = VehicleModel::whereHas('vehicles')->pluck('license_plate', 'id')->toArray();
		$data['banks'] = BankAccount::select("id", DB::raw("CONCAT(bank,'(',account_no,')') as name"))->whereHas('emi_banks')->pluck('name', 'id')->toArray();
		$data['request'] = $request->all();
		$data['result'] = "";

		// dd($data);
		return view('reports.vehicle-emi', $data);
	}

	public function vehicleEmi_print(Request $request)
	{
		// dd($request->all());
		$search_type = $request->search_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;


		// dd($search_type);

		//where[] Array construction
		$where = array();
		if (!empty($request->vehicle_id)) $where['vehicle_id'] = $request->vehicle_id;
		if (!empty($request->driver_id)) $where['driver_id'] = $request->driver_id;
		if (!empty($request->bank_id)) $where['bank_id'] = $request->driver_id;

		//getting from and to date
		if (empty($request->from_date))
			$from_date = EmiModel::orderBy($search_type, 'ASC')->take(1)->first()->$search_type;

		if (empty($request->to_date))
			$to_date = EmiModel::orderBy($search_type, 'DESC')->take(1)->first()->$search_type;

		$emiData = !empty($where) ? EmiModel::where($where)->descending() : EmiModel::descending();
		// dd($emiData->get());
		if (!empty($request->reference_no))
			$emiData = $emiData->whereBetween($search_type, [$from_date, $to_date])->where('reference_no', 'LIKE', "%" . $request->reference_no . "%");
		else
			$emiData = $emiData->whereBetween($search_type, [$from_date, $to_date]);

		// dd($emiData->get());
		// dd($from_date, $to_date);


		$data['emiData'] = $emiData->get();
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;

		// dd($data);
		return view('reports.print_vehicle-emi', $data);
	}

	// Leave report starts
	public function leaveReport()
	{
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		$data['request'] = null;
		// dd($data);
		return view('leaves.report', $data);
	}

	public function leaveReport_post(Request $request)
	{
		// dd($request->all());
		$driver_id = $request->get('driver_id');
		if ($request->get('date1') == null)
			$start = Leave::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Leave::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));


		if (!empty($driver_id)) {
			$leaves = Leave::where('driver_id', $driver_id)->whereBetween('date', [$start, $end]);
		} else {
			$leaves = Leave::whereBetween('date', [$start, $end]);
		}

		$data['result'] = "";
		$data['driver_id'] = $driver_id;
		$data['leaves'] = $leaves->orderBy('date', 'DESC')->get();
		$data['total_present'] = $data['leaves']->count();
		$data['total_absent'] = $data['leaves']->where('is_present', 2)->count();
		$data['request'] = $request->all();
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('leaves.report', $data);
	}

	public function leaveReport_print(Request $request)
	{
		// dd($request->all());
		// dd($request->all());
		$driver_id = $request->get('driver_id');
		if ($request->get('date1') == null)
			$start = Leave::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Leave::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));


		if (!empty($driver_id)) {
			$leaves = Leave::where('driver_id', $driver_id)->whereBetween('date', [$start, $end]);
		} else {
			$leaves = Leave::whereBetween('date', [$start, $end]);
		}

		$data['result'] = "";
		$data['driver_id'] = $driver_id;
		$data['leaves'] = $leaves->orderBy('date', 'DESC')->get();
		$data['total_present'] = $data['leaves']->count();
		$data['total_absent'] = $data['leaves']->where('is_present', 2)->count();
		$data['request'] = $request->all();
		$data['from_date'] = $start;
		$data['to_date'] = $end;
		$data['driver_data'] = User::where('id', $driver_id)->first();
		// dd($data);
		return view('leaves.print-report', $data);
	}
	// Leave report ends

	//transaction report starts
	public function transactionReport()
	{
		//dd('zzzzzzzz');
		$index['from'] = Params::where('code', 'PaymentFrom')->pluck('label', 'id');
		$index['payment_type'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$index['request'] = null;
		// dd($index);

		return view('transactions.reportDebitCredit', $index);
	}

	public function transactionReport_post(Request $request)
	{
		//dd($request->all());
		$from = $request->get('from');
		$payment_type = $request->get('payment_type');
		$from_date = $request->get('from_date');
		$to_date = $request->get('to_date');

		// $from_date = empty($from_date) ? date("Y-m-d", strtotime(Transaction::orderBy('created_at', 'asc')->take(1)->first('created_at')->created_at)) : $from_date;
		// $to_date = empty($to_date) ? date("Y-m-d", strtotime(Transaction::orderBy('created_at', 'desc')->take(1)->first('created_at')->created_at)) : $to_date;

		// dd($from_date, $to_date);

		$where_array = array();
		if (!empty($from)) $where_array['param_id'] = $from;
		if (!empty($payment_type)) $where_array['type'] = $payment_type;

		// dd($where_array);

		if (is_array($where_array) && !empty($where_array))
			$data = Transaction::where($where_array)->orderBy('id', 'DESC');
		else
			$data = Transaction::orderBy('id', 'DESC');

		$transactionData  = $data->get();

		// dd($transaction);
		foreach ($transactionData as $key => $value) {
			$result = Helper::getActualTransactionDate($value->from_id, $value->param_id);
			$value->dateof = $result->date;
			$value->org = $result->org;
		}
		$filtered = $transactionData->where('dateof', '!=', null)->sortBy('dateof')->flatten();
		// dd($filtered);
		// dd($filtered->take(10));
		if (empty($from_date))
			$start = !empty($filtered->first()) ? date("Y-m-d", strtotime($filtered->first()->dateof)) : null;
		else
			$start = date("Y-m-d", strtotime($from_date));

		if (empty($to_date))
			$end = !empty($filtered->reverse()->first()) ? date("Y-m-d", strtotime($filtered->reverse()->first()->dateof)) : null;
		else
			$end = date("Y-m-d", strtotime($to_date));

		// dd($start, $end);
		$transaction  = $filtered->whereBetween('dateof', [$start, $end]);



		// dd($transaction);

		$index['transaction'] = $transaction->reverse()->values();
		$index['from'] = Params::where('code', 'PaymentFrom')->pluck('label', 'id');
		$index['payment_type'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$index['result'] = "";
		$index['sumoftotal'] = !empty($transaction) ? $transaction->sum('total') : 0;
		$index['request'] = $request->all();
		// dd($index['transaction']->first());
		// dd($index);
		return view('transactions.reportDebitCredit', $index);
	}

	public function transactionReport_print(Request $request)
	{
		//dd($request->all());
		$from = $request->get('from');
		$payment_type = $request->get('payment_type');
		$from_date = $request->get('from_date');
		$to_date = $request->get('to_date');

		// $from_date = empty($from_date) ? date("Y-m-d", strtotime(Transaction::orderBy('created_at', 'asc')->take(1)->first('created_at')->created_at)) : $from_date;
		// $to_date = empty($to_date) ? date("Y-m-d", strtotime(Transaction::orderBy('created_at', 'desc')->take(1)->first('created_at')->created_at)) : $to_date;

		// dd($from_date, $to_date);

		$where_array = array();
		if (!empty($from)) $where_array['param_id'] = $from;
		if (!empty($payment_type)) $where_array['type'] = $payment_type;

		// dd($where_array);

		if (is_array($where_array) && !empty($where_array))
			$data = Transaction::where($where_array)->orderBy('id', 'DESC');
		else
			$data = Transaction::orderBy('id', 'DESC');

		$transactionData  = $data->get();

		// dd($transaction);
		foreach ($transactionData as $key => $value) {
			$result = Helper::getActualTransactionDate($value->from_id, $value->param_id);
			$value->dateof = $result->date;
			$value->org = $result->org;
		}
		$filtered = $transactionData->where('dateof', '!=', null)->sortBy('dateof')->flatten();
		// dd($filtered);
		// dd($filtered->take(10));
		if (empty($from_date))
			$start = !empty($filtered->first()) ? date("Y-m-d", strtotime($filtered->first()->dateof)) : null;
		else
			$start = date("Y-m-d", strtotime($from_date));

		if (empty($to_date))
			$end = !empty($filtered->reverse()->first()) ? date("Y-m-d", strtotime($filtered->reverse()->first()->dateof)) : null;
		else
			$end = date("Y-m-d", strtotime($to_date));

		// dd($start, $end);
		$transaction  = $filtered->whereBetween('dateof', [$start, $end]);



		// dd($transaction);

		$index['transaction'] = $transaction->reverse()->values();
		$index['from'] = Params::where('code', 'PaymentFrom')->pluck('label', 'id');
		$index['payment_type'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$index['result'] = "";
		$index['sumoftotal'] = !empty($transaction) ? $transaction->sum('total') : 0;
		$index['request'] = $request->all();

		return view('transactions.reportDebitCredit-print', $index);
	}

	//transaction report ends

	//salary advance report starts

	public function salaryAdvanceReport()
	{
		$yearArr = array();
		$dyear = DailyAdvance::whereNotNull('date')->distinct()->get([DB::raw('YEAR(date) as date')]);
		foreach ($dyear as $year) {
			// dd($year->date);
			$yearArr[$year->date] = $year->date;
		}
		$index['drivers'] = User::whereUserType('D')->orderByName()->pluck('name', 'id');

		$index['year'] = count($yearArr) > 0 ? $yearArr : [date("Y") => date("Y")];
		$index['month'] = Helper::getMonths();
		$index['request'] = null;
		$index['year_select'] = null;
		$index['month_select'] = null;
		// dd($index);
		return view('daily_advance.report', $index);
	}

	public function salaryAdvanceReport_post(Request $request)
	{

		$driver = $request->driver;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
		if (empty($from_date)) {
			$exists = DailyAdvance::orderBy('date', 'ASC')->take(1)->exists();
			if ($exists)
				$from_date = DailyAdvance::orderBy('date', 'ASC')->take(1)->first('date')->date;
			else
				$from_date = null;
		}
		if (empty($to_date)) {
			$exists = DailyAdvance::orderBy('date', 'DESC')->take(1)->exists();
			if ($exists)
				$to_date = DailyAdvance::orderBy('date', 'DESC')->take(1)->first('date')->date;
			else
				$to_date = null;
		}
		// $abc = [$driver, $from_date, $to_date];
		// dd($abc);

		if (!empty($driver))
			$dailyAdvance = DailyAdvance::where('driver_id', $driver)->whereBetween('date', [$from_date, $to_date]);
		else
			$dailyAdvance = DailyAdvance::whereBetween('date', [$from_date, $to_date]);

		$advanceYear = DailyAdvance::whereNotNull('date')->distinct()->get([DB::raw('YEAR(date) as date')]);
		foreach ($advanceYear as $year) {
			// dd($year->date);
			$yearArr[$year->date] = $year->date;
		}
		$index['years'] = !empty($yearArr) ? $yearArr : [date("Y") => date("Y")];
		$index['advances'] = $dailyAdvance->orderBy('date', 'DESC')->get();
		$index['drivers'] = User::whereUserType('D')->orderByName()->pluck('name', 'id');
		$index['request'] = $request->all();

		$index['driver_id'] = $driver;
		$index['result'] = "";
		// dd($index);
		return view('daily_advance.report', $index);
	}
	public function print_salaryAdvance(Request $request)
	{
		$driver = $request->driver;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
		if (empty($from_date)) {
			$exists = DailyAdvance::orderBy('date', 'ASC')->take(1)->exists();
			if ($exists)
				$from_date = DailyAdvance::orderBy('date', 'ASC')->take(1)->first('date')->date;
			else
				$from_date = null;
		}
		if (empty($to_date)) {
			$exists = DailyAdvance::orderBy('date', 'DESC')->take(1)->exists();
			if ($exists)
				$to_date = DailyAdvance::orderBy('date', 'DESC')->take(1)->first('date')->date;
			else
				$to_date = null;
		}
		// $abc = [$driver, $from_date, $to_date];
		// dd($abc);

		if (!empty($driver))
			$dailyAdvance = DailyAdvance::where('driver_id', $driver)->whereBetween('date', [$from_date, $to_date]);
		else
			$dailyAdvance = DailyAdvance::whereBetween('date', [$from_date, $to_date]);


		$index['advances'] = $dailyAdvance->orderBy('date', 'DESC')->get();
		$index['date'] = collect(['from_date' => $from_date, 'to_date' => $to_date]);
		return view('daily_advance.report-print', $index);
	}
	//salary advance report ends

	// other advance report starts

	public function otherAdvanceReport()
	{
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		$data['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$data['status'] = [1 => "Completed", 2 => "In Progress", "3" => "Not Yet Done"];
		$data['request'] = null;
		// dd($data);
		return view('other_advance.report', $data);
	}

	public function otherAdvanceReport_post(Request $request)
	{
		// dd($request->all());
		$driver_id = $request->get('driver_id');
		$method = $request->get('method');
		$status = $request->get('status');

		if ($request->get('date1') == null)
			$start = OtherAdvance::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = OtherAdvance::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		//where condition
		$where = array();
		if (!empty($driver_id)) $where['driver_id'] = $driver_id;
		if (!empty($method)) $where['method'] = $method;
		if (!empty($status)) $where['is_adjusted'] = $status == 3 ? null : $status;
		// dd($where);
		if (!empty($driver_id) || !empty($method) || !empty($status)) {
			$otherAdvance = OtherAdvance::where($where)->whereBetween('date', [$start, $end]);
		} else {
			$otherAdvance = OtherAdvance::whereBetween('date', [$start, $end]);
		}

		$data['result'] = "";
		$data['other_adv'] = $otherAdvance->orderBy("id", "DESC")->get();
		$data['drivers'] = User::where('user_type', 'D')->orderByName()->pluck('name', 'id');
		$data['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$data['status'] = [1 => "Completed", 2 => "In Progress", "3" => "Not Yet Done"];
		$data['request'] = $request->all();
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('other_advance.report', $data);
	}

	public function otherAdvanceReport_print(Request $request)
	{
		// dd($request->all());
		$driver_id = $request->get('driver_id');
		$method = $request->get('method');
		$status = $request->get('status');

		if ($request->get('date1') == null)
			$start = OtherAdvance::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = OtherAdvance::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		//where condition
		$where = array();
		if (!empty($driver_id)) $where['driver_id'] = $driver_id;
		if (!empty($method)) $where['method'] = $method;
		if (!empty($status)) $where['is_adjusted'] = $status == 3 ? null : $status;
		// dd($where);
		if (!empty($driver_id) || !empty($method) || !empty($status)) {
			$otherAdvance = OtherAdvance::where($where)->whereBetween('date', [$start, $end]);
		} else {
			$otherAdvance = OtherAdvance::whereBetween('date', [$start, $end]);
		}

		$data['result'] = "";
		$data['other_adv'] = $otherAdvance->orderBy("id", "DESC")->get();
		$data['drivers'] = User::where('user_type', 'D')->pluck('name', 'id');
		$data['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$data['status'] = [1 => "Completed", 2 => "In Progress", "3" => "Not Yet Done"];
		$data['request'] = $request->all();
		$data['from_date'] = $start;
		$data['to_date'] = $end;
		// dd($data);
		return view('other_advance.print-report', $data);
	}

	// other advance report ends

	// other adjust report starts

	public function otherAdjust()
	{
		$data['heads'] = OtherAdjust::groupBy('head')->pluck('head', 'head');
		$data['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$data['types'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$data['banks'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"), 'id')->pluck('name', 'id');
		$data['request'] = null;
		// dd($data);
		return view('other_adjust.report', $data);
	}

	public function otherAdjust_post(Request $request)
	{
		// dd($request->all());
		$head = $request->get('head');
		$method = $request->get('method');
		$ref_no = $request->get('ref_no');
		$type = $request->get('type');
		$bank_id = $request->get('bank_id');

		if ($request->get('date1') == null)
			$start = OtherAdjust::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = OtherAdjust::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		//where condition
		$where = array();
		if (!empty($head)) $where['head'] = $head;
		if (!empty($method)) $where['method'] = $method;
		if (!empty($type)) $where['type'] = $type;
		if (!empty($bank_id)) $where['bank_id'] = $bank_id;

		// dd($where);
		if (!empty($head) || !empty($method) || !empty($type) || !empty($bank_id)) {
			$otherAdjust = OtherAdjust::where($where)->whereBetween('date', [$start, $end]);
		} else {
			$otherAdjust = OtherAdjust::whereBetween('date', [$start, $end]);
		}
		$otherAdjust->where('ref_no', 'LIKE', "%$ref_no%");
		$data['result'] = "";
		$data['other_adjsqls'] = $otherAdjust->orderBy("id", "DESC")->toSql();
		$data['other_adj'] = $otherAdjust->orderBy("id", "DESC")->get();
		$data['heads'] = OtherAdjust::groupBy('head')->orderByName()->pluck('head', 'head');
		$data['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$data['types'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$data['banks'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"), 'id')->pluck('name', 'id');
		$data['request'] = $request->all();
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('other_adjust.report', $data);
	}

	public function otherAdjust_print(Request $request)
	{
		$head = $request->get('head');
		$method = $request->get('method');
		$ref_no = $request->get('ref_no');
		$type = $request->get('type');
		$bank_id = $request->get('bank_id');

		if ($request->get('date1') == null)
			$start = OtherAdjust::orderBy('date', 'asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = OtherAdjust::orderBy('date', 'desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		//where condition
		$where = array();
		if (!empty($head)) $where['head'] = $head;
		if (!empty($method)) $where['method'] = $method;
		if (!empty($type)) $where['type'] = $type;
		if (!empty($bank_id)) $where['bank_id'] = $bank_id;

		// dd($where);
		if (!empty($head) || !empty($method) || !empty($type) || !empty($bank_id)) {
			$otherAdjust = OtherAdjust::where($where)->whereBetween('date', [$start, $end]);
		} else {
			$otherAdjust = OtherAdjust::whereBetween('date', [$start, $end]);
		}
		$otherAdjust->where('ref_no', 'LIKE', "%$ref_no%");
		$data['result'] = "";
		$data['other_adjsqls'] = $otherAdjust->orderBy("id", "DESC")->toSql();
		$data['other_adj'] = $otherAdjust->orderBy("id", "DESC")->get();
		$data['heads'] = OtherAdjust::groupBy('head')->pluck('head', 'head');
		$data['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
		$data['types'] = Params::where('code', 'PaymentType')->pluck('label', 'id');
		$data['banks'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"), 'id')->pluck('name', 'id');
		$data['request'] = $request->all();
		$data['from_date'] = $start;
		$data['to_date'] = $end;
		// dd($data);
		return view('other_adjust.print-report', $data);
	}

	// other adjust report ends

	// vehicle overview stats
	public function vehicleOverview()
	{
		$data['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name', 'id');
		$data['request'] = null;
		// dd($data);
		return view('vehicles.report', $data);
	}

	public function vehicleOverview_post(Request $request)
	{
		// dd($request->all());
		$data['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name', 'id');
		// $data['bookings'] = Bookings::select(DB::raw("count(id) a  totalbooking,sum()"));
		if ($request->get('date1') == null)
			$start = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup', 'ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup', 'DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
		// dd($start,$end);

		// Bookings
		$vehicle_id = $request->get('vehicle_id');
		$bookings = Bookings::where('vehicle_id', $vehicle_id)->whereBetween('pickup', [$start, $end])->get();

		// dd($bookings);
		foreach ($bookings as $b) {
			$book['kms'][] = explode(" ", $b->getMeta('distance'))[0];
			$book['fuel'][] = $b->getMeta('pet_required');
			$book['price'][] = $b->getMeta('total_price');
		}
		$book['totalbooking'] = $bookings->count();
		$book['totalkms'] = isset($book['kms']) && count($book['kms']) > 0 ? array_sum($book['kms']) : 0.00;
		$book['totalfuel'] = isset($book['fuel']) && count($book['fuel']) > 0 ? array_sum($book['fuel']) : 0.00;
		$book['totalprice'] = isset($book['price']) && count($book['price']) > 0 ? array_sum($book['price']) : 0.00;

		//Fuel
		$fuelModel = FuelModel::where('vehicle_id', $vehicle_id)->whereBetween('date', [$start, $end])->get();
		$fuelArray = array();
		foreach ($fuelModel as $f) {
			// if(in_array($f->fuel_type,$fuelArray)){
			$fuelName = FuelType::find($f->fuel_type)->fuel_name;
			$fuelArray[$fuelName]['id'][] = $f->id;
			$fuelArray[$fuelName]['ltr'][] = $f->qty;
			// $fuelArray[$fuelName]['cost'][] = $f->cost_per_unit;
			$fuelArray[$fuelName]['total'][] = $f->qty * $f->cost_per_unit;
		}

		// Advance
		$advanceBookings = Bookings::where('vehicle_id', $vehicle_id)->whereBetween('pickup', [$start, $end])->meta()->where(function ($query) {
			$query->where('bookings_meta.key', '=', 'advance_pay')
				->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
		})->get();
		$advance = array();
		foreach ($advanceBookings as $ad) {
			$advance['amount'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
			$advance['times'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
			$advance['id'][] = $ad->id;
			//   $advance[] = 
		}
		// dd($advance["id"]);
		$advance['times'] = isset($advance['times']) ? $advance['times'] : 0;
		$advance['id'] = isset($advance['id']) ? $advance['id'] : [];
		$advance['details'] = AdvanceDriver::select('id', 'param_id', DB::raw('count(value) as times'), DB::raw('SUM(value) as amount'))->whereIn('booking_id', $advance['id'])->orderBy('param_id')->groupBy('param_id')->get();
		foreach ($advance['details'] as $det) {
			$det->label = $det->param_name->label;
		}
		$workorders = WorkOrders::where('vehicle_id', $vehicle_id)->whereBetween('required_by', [$start, $end])->get();
		// dd($start,$end,$workorders);
		$prepArray = array();
		foreach ($workorders as $wo) {
			$prepArray['gtotal'][] = empty($wo->grand_total) ? $wo->price : $wo->grand_total;
			$prepArray['cgst'][] = empty($wo->cgst) ? $wo->cgst : $wo->cgst;
			$prepArray['sgst'][] = empty($wo->sgst) ? $wo->sgst : $wo->sgst;
			$prepArray['vendors'][] = $wo->vendor_id;
			$prepArray['status'][$wo->status][] = $wo->status;
			$prepArray['id'][] = $wo->id;
		}
		$data['wo'] = Helper::toCollection([
			'count' => isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? count($prepArray['gtotal']) : 0,
			'grand_total' => isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? array_sum($prepArray['gtotal']) : 0,
			'cgst' => isset($prepArray['cgst']) && !empty($prepArray['cgst']) ? array_sum($prepArray['cgst']) : 0,
			'sgst' => isset($prepArray['sgst']) && !empty($prepArray['sgst']) ? array_sum($prepArray['sgst']) : 0,
			'vendors' => isset($prepArray['vendors']) && !empty($prepArray['vendors']) ? count(array_unique($prepArray['sgst'])) : 0,
			'status' => isset($prepArray['status']) && count($prepArray['status']) > 0 ? $prepArray['status'] : []
		]);
		// Work order/Part
		// dd($data['workorders']);
		// dd($prepArray);
		$workOrderIds = isset($prepArray['id']) && count($prepArray['id']) > 0 ? $prepArray['id'] : [];
		$data['partsUsed'] = PartsUsedModel::select('part_id', DB::raw('SUM(total) as total'), DB::raw('SUM(qty) as qty'))->whereIn('work_id', $workOrderIds)->groupBy('part_id')->get();
		//Work order/Part Ends
		// dd($data);
		// foreach($data['partsUsed'] as $pd){
		//     dd($pd->part_id);
		//     empty($pd->part_id) ?? dd(123);
		//     // dd(PartsModel::find($pd->part_id)->title);
		//     $pd->parts_name = PartsModel::find($pd->part_id)->title;
		// }
		// Tranactions
		// $data['fuel'] = $fuelFinal;
		$data['request'] = $request->all();
		$data['vehicle'] = VehicleModel::where('id', $request->vehicle_id)->first();
		$data['from_date'] = $start;
		$data['to_date'] = $end;
		$data['book'] = Helper::toCollection($book);
		$data['fuels'] = Helper::toCollection($fuelArray);
		$data['advances'] = Helper::toCollection($advance);
		$data['result'] = "";
		// dd($data);
		return view('vehicles.report', $data);
	}

	public function vehicleOverview_print(Request $request)
	{
		$data['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name', 'id');
		// $data['bookings'] = Bookings::select(DB::raw("count(id) a  totalbooking,sum()"));
		if ($request->get('date1') == null)
			$start = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup', 'ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup', 'DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
		// dd($start,$end);

		// Bookings
		$vehicle_id = $request->get('vehicle_id');
		$bookings = Bookings::where('vehicle_id', $vehicle_id)->whereBetween('pickup', [$start, $end])->get();

		// dd($bookings);
		foreach ($bookings as $b) {
			$book['kms'][] = explode(" ", $b->getMeta('distance'))[0];
			$book['fuel'][] = $b->getMeta('pet_required');
			$book['price'][] = $b->getMeta('total_price');
		}
		$book['totalbooking'] = $bookings->count();
		$book['totalkms'] = isset($book['kms']) && count($book['kms']) > 0 ? array_sum($book['kms']) : 0.00;
		$book['totalfuel'] = isset($book['fuel']) && count($book['fuel']) > 0 ? array_sum($book['fuel']) : 0.00;
		$book['totalprice'] = isset($book['price']) && count($book['price']) > 0 ? array_sum($book['price']) : 0.00;

		//Fuel
		$fuelModel = FuelModel::where('vehicle_id', $vehicle_id)->whereBetween('date', [$start, $end])->get();
		$fuelArray = array();
		foreach ($fuelModel as $f) {
			// if(in_array($f->fuel_type,$fuelArray)){
			$fuelName = FuelType::find($f->fuel_type)->fuel_name;
			$fuelArray[$fuelName]['id'][] = $f->id;
			$fuelArray[$fuelName]['ltr'][] = $f->qty;
			// $fuelArray[$fuelName]['cost'][] = $f->cost_per_unit;
			$fuelArray[$fuelName]['total'][] = $f->qty * $f->cost_per_unit;
		}

		// Advance
		$advanceBookings = Bookings::where('vehicle_id', $vehicle_id)->whereBetween('pickup', [$start, $end])->meta()->where(function ($query) {
			$query->where('bookings_meta.key', '=', 'advance_pay')
				->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
		})->get();
		$advance = array();
		foreach ($advanceBookings as $ad) {
			$advance['amount'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
			$advance['times'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
			$advance['id'][] = $ad->id;
			//   $advance[] = 
		}
		// dd($advance["id"]);
		$advance['times'] = isset($advance['times']) ? $advance['times'] : 0;
		$advance['id'] = isset($advance['id']) ? $advance['id'] : [];
		$advance['details'] = AdvanceDriver::select('id', 'param_id', DB::raw('count(value) as times'), DB::raw('SUM(value) as amount'))->whereIn('booking_id', $advance['id'])->orderBy('param_id')->groupBy('param_id')->get();
		foreach ($advance['details'] as $det) {
			$det->label = $det->param_name->label;
		}
		$workorders = WorkOrders::where('vehicle_id', $vehicle_id)->whereBetween('required_by', [$start, $end])->get();
		// dd($start,$end,$workorders);
		$prepArray = array();
		foreach ($workorders as $wo) {
			$prepArray['gtotal'][] = empty($wo->grand_total) ? $wo->price : $wo->grand_total;
			$prepArray['cgst'][] = empty($wo->cgst) ? $wo->cgst : $wo->cgst;
			$prepArray['sgst'][] = empty($wo->sgst) ? $wo->sgst : $wo->sgst;
			$prepArray['vendors'][] = $wo->vendor_id;
			$prepArray['status'][$wo->status][] = $wo->status;
			$prepArray['id'][] = $wo->id;
		}
		$data['wo'] = Helper::toCollection([
			'count' => isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? count($prepArray['gtotal']) : 0,
			'grand_total' => isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? array_sum($prepArray['gtotal']) : 0,
			'cgst' => isset($prepArray['cgst']) && !empty($prepArray['cgst']) ? array_sum($prepArray['cgst']) : 0,
			'sgst' => isset($prepArray['sgst']) && !empty($prepArray['sgst']) ? array_sum($prepArray['sgst']) : 0,
			'vendors' => isset($prepArray['vendors']) && !empty($prepArray['vendors']) ? count(array_unique($prepArray['sgst'])) : 0,
			'status' => isset($prepArray['status']) && count($prepArray['status']) > 0 ? $prepArray['status'] : []
		]);
		// Work order/Part
		// dd($data['workorders']);
		// dd($prepArray);
		$workOrderIds = isset($prepArray['id']) && count($prepArray['id']) > 0 ? $prepArray['id'] : [];
		$data['partsUsed'] = PartsUsedModel::select('part_id', DB::raw('SUM(total) as total'), DB::raw('SUM(qty) as qty'))->whereIn('work_id', $workOrderIds)->groupBy('part_id')->get();
		//Work order/Part Ends
		// dd($data);
		// foreach($data['partsUsed'] as $pd){
		//     dd($pd->part_id);
		//     empty($pd->part_id) ?? dd(123);
		//     // dd(PartsModel::find($pd->part_id)->title);
		//     $pd->parts_name = PartsModel::find($pd->part_id)->title;
		// }
		// Tranactions
		// $data['fuel'] = $fuelFinal;
		$data['request'] = $request->all();
		$data['vehicle'] = VehicleModel::where('id', $request->vehicle_id)->first();
		$data['from_date'] = $start;
		$data['to_date'] = $end;
		$data['book'] = Helper::toCollection($book);
		$data['fuels'] = Helper::toCollection($fuelArray);
		$data['advances'] = Helper::toCollection($advance);
		$data['result'] = "";
		// dd($data);
		return view('vehicles.print_report', $data);
	}
	// vehicle overview ends

	// document renew starts

	public function documentRenewReport()
	{
		// dd($_POST);

		$data['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)
			->pluck('vehicle_name', 'id');
		// dd(FuelType::pluck('fuel_name','id'));
		// dd($data['fuel']);
		$data['vehicle_id'] = "";
		$data['documents'] = Params::where('code', 'RenewDocuments')->pluck('label', 'id');
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;
		// dd($data);
		return view('vehicle_docs.report', $data);
	}

	public function documentRenewReport_post(Request $request)
	{

		// dd($request->all());

		$data['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),
			'id'
		)
			->pluck('vehicle_name', 'id');

		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['documents'] = $documents = $request->get('documents');

		if ($request->get('date1') == null)
			$start = VehicleDocs::orderBy('date', 'asc')->take(1)->exists() ? VehicleDocs::orderBy('date', 'asc')->take(1)->first('date')->date : '';
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = VehicleDocs::orderBy('date', 'desc')->take(1)->exists() ? VehicleDocs::orderBy('date', 'desc')->take(1)->first('date')->date : '';
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		// dd($start);
		// dd($end);



		if (!empty($vehicle_id) && !empty($documents)) {
			$data['docs'] = VehicleDocs::where(['vehicle_id' => $vehicle_id, 'param_id' => $documents])->whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		} else if (!empty($vehicle_id) && empty($documents)) {
			$data['docs'] = VehicleDocs::where(['vehicle_id' => $vehicle_id])->whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		} else if (empty($vehicle_id) && !empty($documents)) {
			$data['docs'] = VehicleDocs::where(['param_id' => $documents])->whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		} else {
			$data['docs'] = VehicleDocs::whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		}
		// dd($data);

		// Helper::totalBookingIncome($vehicle_id);
		// $data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])->whereBetween('created_at', [$start, $end])->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();
		// dd();
		$data['documents'] = Params::where('code', 'RenewDocuments')->pluck('label', 'id');
		$data['result'] = "";
		$data['request'] = $request->all();
		$data['dates'] = [$start, $end];
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('vehicle_docs.report', $data);
	}

	public function documentRenewReport_print(Request $request)
	{

		// dd($request->all());

		$data['vehicle'] = $vehicle = VehicleModel::find($request->get('vehicle_id'));
		$data['docs'] = $docs = VehicleModel::find($request->get('documents'));



		if ($request->get('date1') == null)
			$start = VehicleDocs::orderBy('date', 'asc')->take(1)->exists() ? VehicleDocs::orderBy('date', 'asc')->take(1)->first('date')->date : '';
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = VehicleDocs::orderBy('date', 'desc')->take(1)->exists() ? VehicleDocs::orderBy('date', 'desc')->take(1)->first('date')->date : '';
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		// dd($start);
		// dd($end);



		if (!empty($vehicle) && !empty($docs)) {
			$data['docs'] = VehicleDocs::where(['vehicle_id' => $vehicle->id, 'param_id' => $docs->id])->whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		} else if (!empty($vehicle) && empty($docs)) {
			$data['docs'] = VehicleDocs::where(['vehicle_id' => $vehicle->id])->whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		} else if (empty($vehicle) && !empty($docs)) {
			$data['docs'] = VehicleDocs::where(['param_id' => $docs->id])->whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		} else {
			$data['docs'] = VehicleDocs::whereBetween('date', [$start, $end])->orderBy('id', 'DESC')->get();
		}
		// dd($data);


		$data['documents'] = Params::where('code', 'RenewDocuments')->pluck('label', 'id');
		$data['result'] = "";
		$data['date'] = date("Y-m-d H:i:s");
		$data['requset'] = $request->all();
		$data['dates'] = [$start, $end];
		$data['from_date'] = Helper::getCanonicalDate($start, 'default');
		$data['to_date'] = Helper::getCanonicalDate($end, 'default');
		// dd($data);

		return view('vehicle_docs.print-report', $data);
	}
	// document renew ends
	// upcoming renew starts
	public function upcomingreport()
	{
		// $param_id = "upcoming-renewals";
		// Helper::housekeeping($param_id);
		// $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
		// $collection = collect((array)json_decode($put));
		// $collection = Bookings::hydrate($object);
		// dd($collection);
		// $data['data'] = $collection->flatten();   // get rid of unique_id_XXX


		$vehiArray = array();
		$vehicles =  VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('in_service', 1)->get();
		foreach ($vehicles as $v) {
			$insu_expdate = $v->getMeta('ins_exp_date');
			$insu_dur = $v->getMeta('ins_renew_duration');
			$insu_amt = $v->getMeta('ins_renew_amount');
			$fitness_expdate = $v->getMeta('fitness_expdate');
			$fitness_dur = $v->getMeta('fitness_renew_duration');
			$fitness_amt = $v->getMeta('fitness_renew_amount');
			$roadtax_expdate = $v->getMeta('road_expdate');
			$roadtax_dur = $v->getMeta('roadtax_renew_duration');
			$roadtax_amt = $v->getMeta('roadtax_renew_amount');
			$permit_expdate = $v->getMeta('permit_expdate');
			$permit_dur = $v->getMeta('permit_renew_duration');
			$permit_amt = $v->getMeta('permit_renew_amount');
			$pollution_expdate = $v->getMeta('pollution_expdate');
			$pollution_dur = $v->getMeta('pollution_renew_duration');
			$pollution_amt = $v->getMeta('pollution_renew_amount');
			if ((!empty($insu_dur) && !empty($insu_amt) && !empty($insu_expdate)) || (!empty($fitness_dur) && !empty($fitness_amt) && !empty($fitness_expdate)) || (!empty($roadtax_dur) && !empty($roadtax_amt) && !empty($roadtax_expdate)) || (!empty($permit_dur) && !empty($permit_amt)  && !empty($permit_expdate)) || (!empty($pollution_dur) && !empty($pollution_amt) && !empty($pollution_expdate))) {
				// dd($v);
				$v->is_renewable = 1;
				// $vehiArray[$v->id] = $v->name;
				// dd($v);
			} else {
				$v->is_renewable = null;
			}
			// !is_null($v->is_renewable) ?? dd($v) ;
		}



		$data['vehicle_Array'] = $vehicles->where('is_renewable', 1)->flatten()->pluck('name', 'id');
		// dd(FuelType::pluck('fuel_name','id'));
		// dd($data['fuel']);
		// $data['vehicles'] = $vehicles;
		// $data['vehiArray'] = $vehiArray;
		$data['vehicle_id'] = "";
		$data['documents'] = Params::where('code', 'RenewDocuments')->pluck('label', 'id');
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;
		// dd($data);
		return view('vehicle_docs.upcomingreport', $data);
	}
	public function upcomingreport_post(Request $request)
	{
		$vehiArray = array();
		$vehicles =  VehicleModel::select("*", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('in_service', 1)->get();
		foreach ($vehicles as $v) {
			$insu_expdate = $v->getMeta('ins_exp_date');
			$insu_dur = $v->getMeta('ins_renew_duration');
			$insu_amt = $v->getMeta('ins_renew_amount');
			$fitness_expdate = $v->getMeta('fitness_expdate');
			$fitness_dur = $v->getMeta('fitness_renew_duration');
			$fitness_amt = $v->getMeta('fitness_renew_amount');
			$roadtax_expdate = $v->getMeta('road_expdate');
			$roadtax_dur = $v->getMeta('roadtax_renew_duration');
			$roadtax_amt = $v->getMeta('roadtax_renew_amount');
			$permit_expdate = $v->getMeta('permit_expdate');
			$permit_dur = $v->getMeta('permit_renew_duration');
			$permit_amt = $v->getMeta('permit_renew_amount');
			$pollution_expdate = $v->getMeta('pollution_expdate');
			$pollution_dur = $v->getMeta('pollution_renew_duration');
			$pollution_amt = $v->getMeta('pollution_renew_amount');
			if ((!empty($insu_dur) && !empty($insu_amt) && !empty($insu_expdate)) || (!empty($fitness_dur) && !empty($fitness_amt) && !empty($fitness_expdate)) || (!empty($roadtax_dur) && !empty($roadtax_amt) && !empty($roadtax_expdate)) || (!empty($permit_dur) && !empty($permit_amt)  && !empty($permit_expdate)) || (!empty($pollution_dur) && !empty($pollution_amt) && !empty($pollution_expdate))) {
				// dd($v);
				$v->is_renewable = 1;
				//put till date
				// $vehiArray[$v->id] = $v->name;
				// dd($v);
			} else {
				$v->is_renewable = null;
			}
			// !is_null($v->is_renewable) ?? dd($v) ;
		}
		// dd($vehicles->first());
		$data['vehicle_Array'] = $vehicles->where('is_renewable', 1)->flatten()->pluck('name', 'id');
		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['document_id'] = $document_id = $request->get('documents');




		// dd($data['data']);

		if (!empty($vehicle_id))
			$data['data'] = $vehicles->where('is_renewable', 1)->flatten()->where('id', $vehicle_id);
		else
			$data['data'] = $vehicles->where('is_renewable', 1)->flatten();


		$data['docparamArray'] = [
			36 => ['ins_renew_duration', 'ins_renew_amount', 'ins_exp_date'],
			37 => ['fitness_renew_duration', 'fitness_renew_amount', 'fitness_expdate'],
			38 => ['roadtax_renew_duration', 'roadtax_renew_amount', 'road_expdate'],
			39 => ['permit_renew_duration', 'permit_renew_amount', 'permit_expdate'],
			40 => ['pollution_renew_duration', 'pollution_renew_amount', 'pollution_expdate']
		];
		$data['documents'] = Params::where('code', 'RenewDocuments')->pluck('label', 'id');
		$data['result'] = "";
		$data['request'] = $request->all();

		// dd($data['data']->first());
		return view('vehicle_docs.upcomingreport', $data);
	}
	public function print_upcomingreport(Request $request)
	{
		$vehiArray = array();
		$vehicles =  VehicleModel::select("*", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('in_service', 1)->get();
		foreach ($vehicles as $v) {
			$insu_expdate = $v->getMeta('ins_exp_date');
			$insu_dur = $v->getMeta('ins_renew_duration');
			$insu_amt = $v->getMeta('ins_renew_amount');
			$fitness_expdate = $v->getMeta('fitness_expdate');
			$fitness_dur = $v->getMeta('fitness_renew_duration');
			$fitness_amt = $v->getMeta('fitness_renew_amount');
			$roadtax_expdate = $v->getMeta('road_expdate');
			$roadtax_dur = $v->getMeta('roadtax_renew_duration');
			$roadtax_amt = $v->getMeta('roadtax_renew_amount');
			$permit_expdate = $v->getMeta('permit_expdate');
			$permit_dur = $v->getMeta('permit_renew_duration');
			$permit_amt = $v->getMeta('permit_renew_amount');
			$pollution_expdate = $v->getMeta('pollution_expdate');
			$pollution_dur = $v->getMeta('pollution_renew_duration');
			$pollution_amt = $v->getMeta('pollution_renew_amount');
			if ((!empty($insu_dur) && !empty($insu_amt) && !empty($insu_expdate)) || (!empty($fitness_dur) && !empty($fitness_amt) && !empty($fitness_expdate)) || (!empty($roadtax_dur) && !empty($roadtax_amt) && !empty($roadtax_expdate)) || (!empty($permit_dur) && !empty($permit_amt)  && !empty($permit_expdate)) || (!empty($pollution_dur) && !empty($pollution_amt) && !empty($pollution_expdate))) {
				// dd($v);
				$v->is_renewable = 1;
				//put till date
				// $vehiArray[$v->id] = $v->name;
				// dd($v);
			} else {
				$v->is_renewable = null;
			}
			// !is_null($v->is_renewable) ?? dd($v) ;
		}
		// dd($vehicles->first());
		$data['vehicle_Array'] = $vehicles->where('is_renewable', 1)->flatten()->pluck('name', 'id');
		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['document_id'] = $document_id = $request->get('documents');




		// dd($data['data']);

		if (!empty($vehicle_id))
			$data['data'] = $vehicles->where('is_renewable', 1)->flatten()->where('id', $vehicle_id);
		else
			$data['data'] = $vehicles->where('is_renewable', 1)->flatten();


		$data['docparamArray'] = [
			36 => ['ins_renew_duration', 'ins_renew_amount', 'ins_exp_date'],
			37 => ['fitness_renew_duration', 'fitness_renew_amount', 'fitness_expdate'],
			38 => ['roadtax_renew_duration', 'roadtax_renew_amount', 'road_expdate'],
			39 => ['permit_renew_duration', 'permit_renew_amount', 'permit_expdate'],
			40 => ['pollution_renew_duration', 'pollution_renew_amount', 'pollution_expdate']
		];
		$data['documents'] = Params::where('code', 'RenewDocuments')->pluck('label', 'id');
		$data['result'] = "";
		$data['request'] = $request->all();
		return view('vehicle_docs.print-upcomingreport', $data);
	}
	// upcoming renew ends
	// vehicle fuel type starts

	public function fuelTypeReport_vehicle()
	{
		$fuelVehicles = FuelModel::select('vehicle_id')->groupBy('vehicle_id')->pluck('vehicle_id');
		// dd($fuelVehicles);
		$index['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->whereIn('id', $fuelVehicles)->orderBy("name", "ASC")->pluck("name", "id");
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['request'] = null;
		// dd($index);
		return view('fuel.report-vehicle', $index);
	}

	public function fuelTypeReport_vehiclepost(Request $request)
	{
		// dd($request->toArray());
		$vehicle = $request->vehicle;
		$fuel_type = $request->fuel_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

		if (empty($vehicle) && empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'))->groupBy('vehicle_id', 'fuel_type');
		elseif (empty($vehicle))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'))->groupBy('vehicle_id', 'fuel_type');
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vehicle_id', $vehicle)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'))->groupBy('vehicle_id', 'fuel_type');
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vehicle_id', $vehicle)->where('fuel_type', $fuel_type)->select('vehicle_id', 'fuel_type', DB::raw('SUM(qty) as qty'))->groupBy('vehicle_id', 'fuel_type');

		// dd($fuelModel->get());

		// $index['fuelv'] = $fuelModel->orderBy('vehicle_id', 'DESC')->get();
		// //	$index['fuelv'] = $fuelModel->orderBy('vehicle_id','ASC')->get();
		// foreach ($index['fuelv'] as $sum) {
		// 	$fmodel =
		// 		$summation[] = $sum->total_cost;
		// 	$summationqty[] = $sum->qty;
		// }

		$fuel = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', '!=', null)->get();
		$other = $fuelModel->where('fuel_type', '!=', null)->where('vendor_name', null)->get();
		$union = $fuel->union($other);

		foreach ($union as $fs) {
			// dd($fs);
			// $sum = $fs->qty * $fs->cost_per_unit;
			$fmodel = FuelModel::where(['vehicle_id' => $fs->vehicle_id, 'fuel_type' => $fs->fuel_type])->whereBetween('date', [$from_date, $to_date]);

			$fuelSum = 0;
			if ($fmodel->exists()) {
				// dd(1);
				foreach ($fmodel->get() as $anotheri) {
					$sum = $anotheri->qty * $anotheri->cost_per_unit;
					$fuelSum += $sum;
				}
			}
			// dd($fuelSum);
			$fs->total_cost = $fuelSum;
			// $fs->total_costARR=[$fuelSum];
		}

		$fuelVehicles = FuelModel::select('vehicle_id')->groupBy('vehicle_id')->pluck('vehicle_id');
		// $summation = !empty($summation) ? $summation : [0];
		$index['fuelv'] = $union;
		$index['grandtotal_cost'] = $union->sum('total_cost');
		$index['grandtotal_qty'] = $union->sum('qty');
		$index['request'] = $request->all();
		$index['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->whereIn('id', $fuelVehicles)->orderBy("name", "ASC")->pluck("name", "id");
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['result'] = "";
		// dd($index['fuelv']->first());
		return view('fuel.report-vehicle', $index);
	}
	public function fuelTypeReport_vehicleprint(Request $request)
	{
		// dd($request->toArray());
		$vehicle = $request->vehicle;
		$fuel_type = $request->fuel_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
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

	public function view_vehicle_fuel_details($arr)
	{
		// dd($arr);
		$arr = explode(",", $arr);
		if (count($arr) > 0 && $arr[0] != '' && $arr[1] != '') {

			$vehicle_id = $arr[0];
			$fuel_type = $arr[1];

			$from_date = !empty($arr[2]) ? Helper::ymd($arr[2]) : null;
			$to_date = !empty($arr[3]) ? Helper::ymd($arr[3]) : null;
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

	// vehicle fuel type ends


	// fuel type starts

	public function fuelTypeReport()
	{
		//dd('xxx');
		$index['vendors'] = Vendor::pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['request'] = null;

		return view('fuel.report', $index);
	}

	public function fuelTypeReport_post(Request $request)
	{
		// dd($request->all());
		$vendor_id = $request->vendor;
		$fuel_type = $request->fuel_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
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
			$fmodel = FuelModel::where(['vendor_name' => $fs->vendor_name, 'fuel_type' => $fs->fuel_type])->whereBetween('date', [$from_date, $to_date]);

			$fuelSum = 0;
			if ($fmodel->exists()) {
				foreach ($fmodel->get() as $anotheri) {
					$sum = $anotheri->qty * $anotheri->cost_per_unit;
					$fuelSum += $sum;
				}
			}
			$fs->total_cost = $fuelSum;
			// $fs->total_costARR=[$fuelSum];
		}

		$index['fuel'] = $union;

		$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['result'] = '';
		$index['request'] = $request->all();
		// dd($index);
		return view('fuel.report', $index);
	}

	public function fuelTypeReport_print(Request $request)
	{
		$vendor_id = $request->vendor;
		$fuel_type = $request->fuel_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
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
			$fmodel = FuelModel::where(['vendor_name' => $fs->vendor_name, 'fuel_type' => $fs->fuel_type])->whereBetween('date', [$from_date, $to_date]);

			$fuelSum = 0;
			if ($fmodel->exists()) {
				foreach ($fmodel->get() as $anotheri) {
					$sum = $anotheri->qty * $anotheri->cost_per_unit;
					$fuelSum += $sum;
				}
			}
			$fs->total_cost = $fuelSum;
			// $fs->total_costARR=[$fuelSum];
		}

		$index['fuel'] = $union;
		$index['date'] = collect(['from_date' => $from_date, 'to_date' => $to_date]);

		// dd($index);
		return view('fuel.report-print', $index);
	}

	public function view_fuel_details($arr)
	{

		$arr = explode(",", $arr);
		// dd($arr);
		if (count($arr) > 0 && $arr[0] != '' && $arr[1] != '') {

			$vendor_id = $arr[0];
			$fuel_type = $arr[1];

			$from_date = !empty($arr[2]) ? Helper::ymd($arr[2]) : null;
			$to_date = !empty($arr[3]) ? Helper::ymd($arr[3]) : null;
			$from_date = empty($from_date) ? FuelModel::orderBy('date', 'asc')->take(1)->first('date')->date : $from_date;
			$to_date = empty($to_date) ? FuelModel::orderBy('date', 'desc')->take(1)->first('date')->date : $to_date;

			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where(['vendor_name' => $vendor_id, 'fuel_type' => $fuel_type]);
			// dd(FuelModel::whereBetween('date', [$from_date, $to_date])->get());
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
			// dd($index);

			return view('fuel.view_fuel_details', $index);
		} else {
			dd($arr);
		}
	}

	// fuel type ends

	// vendor report starts

	public function vendorReport()
	{
		// dd("report");
		$index['vendors'] = Vendor::whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['request'] = null;
		// dd($index);
		return view('vendors.report', $index);
	}

	public function vendorReport_post(Request $request)
	{
		// dd($request->all());
		$vendor = $request->vendor;
		$fuel_type = $request->fuel_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;

		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'ASC')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'DESC')->take(1)->first('date')->date : $to_date;

		if (empty($vendor) && empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date]);
		elseif (empty($vendor))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type);
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor);
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor)->where('fuel_type', $fuel_type);

		$index['fuel'] = $fuelModel->orderBy('date', 'ASC')->get();
		// $index['fuelTotal'] = $fuelModel->select(DB::raw('SUM(qty) as qty'),DB::raw('AVG(cost_per_unit) as per_unit'),DB::raw('SUM(qty) * AVG(cost_per_unit) as amount'))->first();
		foreach ($index['fuel'] as $sum) {
			$summation[] = $sum->qty * $sum->cost_per_unit;
		}
		$summation = !empty($summation) ? $summation : [0];
		$index['fuelTotal'] = array_sum($summation);
		$index['is_vendor'] = !empty($vendor) ? true : false;
		if ($vendor != '')
			$index['vendorName'] = Vendor::withTrashed()->where('id', $vendor)->first()->name;
		else $index['vendorName'] = null;
		$index['vendors'] = Vendor::withTrashed()->whereIn('type', ['Fuel', 'fuel'])->pluck('name', 'id');
		$index['fuel_types'] = FuelType::pluck('fuel_name', 'id');
		$index['request'] = $request->all();
		$index['result'] = "";
		// dd($index);
		return view('vendors.report', $index);
	}

	public function vendorReport_print(Request $request)
	{
		// dd($request->all());
		$vendor = $request->vendor;
		$fuel_type = $request->fuel_type;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;

		$from_date = empty($from_date) ? FuelModel::orderBy('date', 'ASC')->take(1)->first('date')->date : $from_date;
		$to_date = empty($to_date) ? FuelModel::orderBy('date', 'DESC')->take(1)->first('date')->date : $to_date;

		if (empty($vendor) && empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date]);
		elseif (empty($vendor))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type);
		elseif (empty($fuel_type))
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor);
		else
			$fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor)->where('fuel_type', $fuel_type);

		$index['fuel'] = $fuelModel->orderBy('date', 'ASC')->get();
		// $index['fuelTotal'] = $fuelModel->select(DB::raw('SUM(qty) as qty'),DB::raw('AVG(cost_per_unit) as per_unit'),DB::raw('SUM(qty) * AVG(cost_per_unit) as amount'))->first();
		foreach ($index['fuel'] as $sum) {
			$summation[] = $sum->qty * $sum->cost_per_unit;
		}
		$index['fuelTotal'] = array_sum($summation);
		$index['date'] = collect(['from_date' => $from_date, 'to_date' => $to_date]);
		$index['is_vendor'] = !empty($vendor) ? true : false;
		if ($vendor != '')
			$index['vendorName'] = Vendor::withTrashed()->where('id', $vendor)->first()->name;
		else $index['vendorName'] = null;

		// $index['req'] = $request->all();
		// dd($index);
		return view('vendors.report-print', $index);
	}

	// vendor report ends

	// work order vendor report start


	public function workOrderReport_vendor()
	{
		$workOrder = WorkOrders::orderBy('vendor_id', 'ASC')->groupBy('vendor_id')->get();
		foreach ($workOrder as $work) {
			$ids[] = $work->vendor_id;
		}
		$index['vendors'] = Vendor::whereIn('id', $ids)->pluck('name', 'id');
		$index['status'] = WorkOrders::groupBy('status')->pluck('status', 'status');
		$index['request'] = null;
		// dd($index);

		return view('work_orders.report', $index);
	}

	public function workOrderReport_vendorpost(Request $request)
	{
		// dd($request->all());
		$vendor = $request->vendor;
		$status = $request->status;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
		$from_date = empty($request->from_date) ? WorkOrders::orderBy('required_by', 'ASC')->take(1)->first('required_by')->required_by : $from_date;
		$to_date = empty($request->to_date) ? WorkOrders::orderBy('required_by', 'DESC')->take(1)->first('required_by')->required_by : $to_date;

		if (empty($vendor) && empty($status))
			$workOrder = WorkOrders::whereBetween('required_by', [$from_date, $to_date]);
		else if (empty($vendor))
			$workOrder = WorkOrders::where('status', $status)->whereBetween('required_by', [$from_date, $to_date]);
		else if (empty($status))
			$workOrder = WorkOrders::where('vendor_id', $vendor)->whereBetween('required_by', [$from_date, $to_date]);
		else
			$workOrder = WorkOrders::where(['vendor_id' => $vendor, 'status' => $status])->whereBetween('required_by', [$from_date, $to_date]);
		$vendor_ids = WorkOrders::orderBy('vendor_id', 'ASC')->groupBy('vendor_id')->get();
		// dd($ids);
		foreach ($vendor_ids as $work) {
			$ids[] = $work->vendor_id;
		}
		$index['workOrder'] = $workOrder->get();
		$index['vendors'] = Vendor::whereIn('id', $ids)->pluck('name', 'id');
		$index['status'] = WorkOrders::groupBy('status')->pluck('status', 'status');
		$index['is_vendor'] = !empty($vendor) ? true : false;
		$index['gtotal'] = $workOrder->sum('price');
		$index['result'] = "";
		$index['request'] = $request->all();
		// dd($index);
		return view('work_orders.report', $index);
	}

	public function workOrderReport_vendorprint(Request $request)
	{
		// dd($request->all());
		$vendor = $request->vendor;
		$status = $request->status;
		$from_date = !empty($request->from_date) ? Helper::ymd($request->from_date) : null;
		$to_date = !empty($request->to_date) ? Helper::ymd($request->to_date) : null;
		$from_date = empty($request->from_date) ? WorkOrders::orderBy('required_by', 'ASC')->take(1)->first('required_by')->required_by : $from_date;
		$to_date = empty($request->to_date) ? WorkOrders::orderBy('required_by', 'DESC')->take(1)->first('required_by')->required_by : $to_date;

		if (empty($vendor) && empty($status))
			$workOrder = WorkOrders::whereBetween('required_by', [$from_date, $to_date]);
		else if (empty($vendor))
			$workOrder = WorkOrders::where('status', $status)->whereBetween('required_by', [$from_date, $to_date]);
		else if (empty($status))
			$workOrder = WorkOrders::where('vendor_id', $vendor)->whereBetween('required_by', [$from_date, $to_date]);
		else
			$workOrder = WorkOrders::where(['vendor_id' => $vendor, 'status' => $status])->whereBetween('required_by', [$from_date, $to_date]);


		// dd($ids);
		$index['workOrder'] = $workOrder->get();
		$index['is_vendor'] = !empty($vendor) ? true : false;
		$index['vendorName'] = Vendor::where('id', $vendor)->exists() ? Vendor::where('id', $vendor)->first()->name : "";
		$index['gtotal'] = $workOrder->sum('price');
		$index['date'] = collect(['from_date' => $from_date, 'to_date' => $to_date]);
		$index['result'] = "";
		// dd($index);
		return view('work_orders.report-print', $index);
	}

	// work order vendor report ends

	// service reminder report starts
	public function serviceReminder()
	{
		$data['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name', 'id');
		// $data['veheicles'] =  collect(DB::raw(DB::select("select id, CONCAT(make,'-',model,'-',license_plate) as name from vehicles where deleted_at IS NULL")))->toArray();
		$data['request'] = null;
		// dd($data);
		return view('service_reminder.report', $data);
	}

	public function serviceReminder_post(Request $request)
	{
		// dd($request->all());
		// dd("Post");
		// dd("Drivers Post Report");
		// dd($request->all());
		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name', 'id');
		if ($request->get('date1') == null)
			$start = ServiceReminderModel::orderBy('last_date', 'ASC')->take(1)->first('last_date')->last_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = ServiceReminderModel::orderBy('last_date', 'DESC')->take(1)->first('last_date')->last_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d', strtotime($start));
		$end = date('Y-m-d', strtotime($end));



		if (!empty($vehicle_id))
			$data['services'] = ServiceReminderModel::where('vehicle_id', $vehicle_id)->whereBetween('last_date', [$start, $end])->orderBy('last_date', 'DESC')->get();
		else
			$data['services'] = ServiceReminderModel::whereBetween('last_date', [$start, $end])->orderBy('last_date', 'DESC')->get();



		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['request'] = $request->all();
		// dd($data);
		return view('service_reminder.report', $data);
	}

	public function serviceReminder_print(Request $request)
	{
		$data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
		$data['vehicles'] = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name', 'id');
		if ($request->get('date1') == null)
			$start = ServiceReminderModel::orderBy('last_date', 'ASC')->take(1)->first('last_date')->last_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if ($request->get('date2') == null)
			$end = ServiceReminderModel::orderBy('last_date', 'DESC')->take(1)->first('last_date')->last_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d', strtotime($start));
		$end = date('Y-m-d', strtotime($end));



		if (!empty($vehicle_id))
			$data['services'] = ServiceReminderModel::where('vehicle_id', $vehicle_id)->whereBetween('last_date', [$start, $end])->orderBy('last_date', 'DESC')->get();
		else
			$data['services'] = ServiceReminderModel::whereBetween('last_date', [$start, $end])->orderBy('last_date', 'DESC')->get();

		$data['result'] = "";
		$data['dates'] = [$start, $end];
		$data['request'] = $request->all();
		$data['vehicle'] = VehicleModel::find($vehicle_id);
		$data['from_date'] = Helper::getCanonicalDate($start, 'default');
		$data['to_date'] = Helper::getCanonicalDate($end, 'default');
		// dd($data);
		return view('service_reminder.print_report', $data);
	}
	// service reminder report ends
}
