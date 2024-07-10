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
use App\Model\DailyAdvance;
use App\Model\FuelType;
use App\Model\Payroll;
use App\Model\DriverVehicleModel;
use App\Model\IncomeExpense;
use App\Model\OtherAdvance;
use App\Model\PartsInvoice;
use App\Model\VehicleDocs;
use Auth;
use DB;
use Helper;
use Hash;
use Illuminate\Http\Request;

class ReportsController extends Controller {

	public function expense() {
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

	public function expense_post(Request $request) {

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

	public function expense_print(Request $request) {
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

	public function income() {

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

	public function income_post(Request $request) {
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

	public function income_print(Request $request) {
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

	public function monthly() {

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

	public function delinquent() {
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
	public function vendorPayment()
	{
		// $customers=DB::table('bookings')->join('users','bookings.customer_id','=','users.id')->select('users.name as customer_name','users.id as from')->groupBy('users.name')->get;
		// $customerArray = array();
		// foreach($customers as $cust)
		// {
		// 	$customerArray[$cust->from] = $cust->customer_name;
		// }
		$index['from'] = Vendor::pluck('name','id');
        $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['request'] = null;
        //dd($index);
        return view('reports.vendorPayment',$index);
	}

	public function vendorPayment_post(Request $request){
	   $from = $request->get('from');
	   $payment_type = $request->get('payment_type');
	   $from_date = $request->get('from_date');
	   $to_date = $request->get('to_date'); 
	   
	   $from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
	   $to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;
		
	   $fromchk = empty($from) ? Vendor::pluck('id')->toArray() : $from;
	   $from=Vendor::pluck('name','id');
		if(!empty($payment_type)){
			$transaction = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('type',$payment_type)->get();
		}else{
			$transaction = Transaction::whereBetween('created_at',[$from_date,$to_date])->get();
		}
		//dd($transaction);
		foreach($transaction as $t){
			//dd($t->param_id);
			// if($t->param_id==20)
			// {
			// 	dd($t);
			// }

			if($t->param_id==20){
				$shalom = FuelModel::where('id',$t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_name : null ;			
			}
			elseif($t->param_id==26){
				$shalom = PartsInvoice::where('id',$t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null ;			
			}
			elseif($t->param_id==28){
				$shalom = WorkOrders::where('id',$t->from_id);
				$t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null ;			
			}else{
				$t->org_id =null;
			}
		
		}
		//dd($fromchk);
	if(is_array($fromchk) && count($fromchk)>0)
	{	//array
		//dd($fromchk);
		$filtered = $transaction->where('org_id','!=',null)->whereIn('org_id',$fromchk);
	}
	elseif(!empty($fromchk)) //number
	{
		$filtered = $transaction->where('org_id','!=',null)->where('org_id',$fromchk);
	}
	else 
	{
		$filtered = $transaction->where('org_id','!=',null);
	}
	//dd($filtered);
	// dd($filtered->first());
	   $index['transaction'] = $filtered->sortByDesc('id')->values()->all();
	//    dd($index['transaction']->first());
	   $index['from'] = $from;
	   $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
	   $index['result'] = "";
	   $index['sumoftotal']=$filtered->sum('total');
	   $index['request'] = $request->all();
	  // dd($index);
	   return view('reports.vendorPayment',$index);

   }


   public function vendor_payment(Request $request){
	  // dd($request);
	$from = $request->get('from');
	$payment_type = $request->get('payment_type');
	$from_date = $request->get('from_date');
	$to_date = $request->get('to_date'); 
	
	$from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
	$to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;
	 
	$fromchk = empty($from) ? Vendor::pluck('id')->toArray() : $from;
	$from=Vendor::pluck('name','id');
	 if(!empty($payment_type)){
		 $transaction = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('type',$payment_type)->get();
	 }else{
		 $transaction = Transaction::whereBetween('created_at',[$from_date,$to_date])->get();
	 }
	 //dd($transaction);
	 foreach($transaction as $t){
		 //dd($t->param_id);
		 // if($t->param_id==20)
		 // {
		 // 	dd($t);
		 // }

		 if($t->param_id==20){
			 $shalom = FuelModel::where('id',$t->from_id);
			 $t->org_id = $shalom->exists() ? $shalom->first()->vendor_name : null ;			
		 }
		 elseif($t->param_id==26){
			 $shalom = PartsInvoice::where('id',$t->from_id);
			 $t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null ;			
		 }
		 elseif($t->param_id==28){
			 $shalom = WorkOrders::where('id',$t->from_id);
			 $t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null ;			
		 }else{
			 $t->org_id =null;
		 }
	 
	 }
	 //dd($fromchk);
 	if(is_array($fromchk) && count($fromchk)>0)
 	{	//array
	 //dd($fromchk);
	 $filtered = $transaction->where('org_id','!=',null)->whereIn('org_id',$fromchk);
 	}
 	elseif(!empty($fromchk)) //number
 	{
	 $filtered = $transaction->where('org_id','!=',null)->where('org_id',$fromchk);
 	}
 	else 
 	{
	 $filtered = $transaction->where('org_id','!=',null);
 	}
 	//dd($filtered);
	//	dd($filtered);
	$index['transaction'] = $filtered->sortByDesc('id')->values()->all();
	
	$index['from'] = $from;
	$index['vendor_data'] = Vendor::where("id",$request->get('from'))->first();
	$index['date'] = date('Y-m-d H:i:s');
	$index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
	$index['result'] = "";
    $index['sumoftotal']=$filtered->sum('total');
	$index['request'] = $request->all();
	$index['from_date'] = $from_date;
	$index['to_date'] = $to_date;
    // dd($index);
	return view('reports.vendorPaymentPrint',$index);

	}

	public function customerPayment()
	{
		$customers=DB::table('bookings')->join('users','bookings.customer_id','=','users.id')->select('users.name as customer_name','users.id as from')->groupBy('users.name')->get();
		$customerArray = array();
		foreach($customers as $cust)
		{
			$customerArray[$cust->from] = $cust->customer_name;
		}
		$index['from'] = $customerArray;
        $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['request'] = null;
        //dd($index);
        return view('reports.customerPayment',$index);
	}
	public function customerPayment_post(Request $request){
		$customers=DB::table('bookings')->join('users','bookings.customer_id','=','users.id')->select('users.name as customer_name','users.id as from')->groupBy('users.name')->get();
		$customerArray = array();
		foreach($customers as $cust)
		{
			$customerArray[$cust->from] = $cust->customer_name;
		}

	   $from = $request->get('from');
	   $bookid = Bookings::where('customer_id',$from)->pluck('id');
	   //dd($bookid);
	   $payment_type = $request->get('payment_type');
	   $from_date = $request->get('from_date');
	   $to_date = $request->get('to_date'); 
	   
	   $from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
	   $to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;

	   if(empty($from) && empty($payment_type))
		   {
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null]);
		  
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->sum('total');
		   }
	   elseif(empty($from))
		   {
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type,'deleted_at'=>null]);
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type,'deleted_at'=>null])->sum('total');
		   }
	   elseif(empty($payment_type))
		   {
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->whereIn('from_id',$bookid);
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->whereIn('from_id',$bookid)->sum('total');
		   }
	   else{
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type])->whereIn('from_id',$bookid);
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type,'deleted_at'=>null])->whereIn('from_id',$bookid)->sum('total');
		   }

	   $transaction  = $transmodel->orderBy("id","DESC")->get();
	   
	   $index['transaction'] = $transaction;
	   $index['from'] = $customerArray;
	   $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
	   $index['result'] = "";
	   $index['sumoftotal']=$sumoftotal;
	   $index['request'] = $request->all();
	  // dd($index);
	   return view('reports.customerPayment',$index);

   }
   public function customer_payment(Request $request){

	$customers=DB::table('bookings')->join('users','bookings.customer_id','=','users.id')->select('users.name as customer_name','users.id as from')->groupBy('users.name')->get();
		$customerArray = array();
		foreach($customers as $cust)
		{
			$customerArray[$cust->from] = $cust->customer_name;
		}

	    $from = $request->get('from');
	    $bookid = Bookings::where('customer_id',$from)->pluck('id');
	//    $customerName = DB::table('users')
	//    ->select('name')
	//    ->where('id','=', $from)
	//    ->get();
	   //dd($bookid);
	   $payment_type = $request->get('payment_type');
	   $from_date = $request->get('from_date');
	   $to_date = $request->get('to_date');
	   
	   $from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
	   $to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;

	   if(empty($from) && empty($payment_type))
		   {
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->select('*');
		  
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->sum('total');
		   }
	   elseif(empty($from))
		   {
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type,'deleted_at'=>null])->select('*');
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type,'deleted_at'=>null])->sum('total');
		   }
	   elseif(empty($payment_type))
		   {
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->whereIn('from_id',$bookid)->select('*');
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'deleted_at'=>null])->whereIn('from_id',$bookid)->sum('total');
		   }
	   else{
		   $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type])->whereIn('from_id',$bookid)->select('*');
		   $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>18,'type'=>$payment_type,'deleted_at'=>null])->whereIn('from_id',$bookid)->sum('total');
		   }

	   $transaction  = $transmodel->orderBy("id","DESC")->get();
	   
	   $index['transaction'] = $transaction;
	   $index['date'] = date('Y-m-d H:i:s');
	   $index['from'] = $customerArray;
	   $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
	   $index['result'] = "";
	   $index['sumoftotal']=$sumoftotal;
	   //$index['customerName']=$customerName;
	   $index['request'] = $request->all();
	  // dd($index);
	   return view('reports.customerPaymentPrint',$index);

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
            DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
            ->pluck('vehicle_name', 'id');
		$data['customers'] = User::where('user_type', 'C')->pluck("name","id");
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;
		
		return view("reports.booking", $data);
	}
	
	public function booking_post(Request $request) {
		$customer_id = $request->customer_id;
		$vehicle_id = $request->vehicle_id;
		$from_date = $request->date1;
		$to_date = $request->date2;
		$from_date = empty($from_date) ? Bookings::orderBy('pickup','asc')->take(1)->first('pickup')->pickup : $from_date;
		$to_date = empty($to_date) ? Bookings::orderBy('pickup','desc')->take(1)->first('pickup')->pickup : $to_date;
		// $abc['vendor_id'] = $vendor_id;
		// $abc['fuel_type'] = $fuel_type;
		// $abc['from_date'] = $from_date;
		// $abc['to_date'] = $to_date;
		// dd($abc);
		//same date search
		if(strtotime($from_date)==strtotime($to_date)){
			$from_date = $from_date." 00:00:00";
			$to_date = $to_date." 23:59:59";
		}
		if(empty($vehicle_id) && empty($customer_id))
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date]);
		elseif(empty($vehicle_id))
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date])->where('customer_id',$customer_id);
		elseif(empty($customer_id))
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date])->where('vehicle_id',$vehicle_id);
		else
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date])->where(['vehicle_id'=>$vehicle_id,'customer_id'=>$customer_id]);
		$total = array();
		foreach($bookings->get() as $bk){
			$total[] = $bk->getMeta('total_price');
			$totalfuel[] = $bk->getMeta('pet_required');
			$totaldistance[] = !empty($bk->getMeta('distance')) ? explode(" ",$bk->getMeta('distance'))[0] : 0;
		}
		
		$index['vehicles'] = VehicleModel::select(
            DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
            ->pluck('vehicle_name', 'id');
		$index['customers'] = User::where('user_type', 'C')->pluck("name","id");
		$index['bookings']=$bookings->orderBy('id','DESC')->get();
		$index['result']='';
		$data['date1'] = null;
		$data['date2'] = null;
		$index['total_price']=array_sum($total);
		$index['total_fuel']=array_sum($totalfuel);
		$index['total_distance']=array_sum($totaldistance);
		$index['request']=$request->all();
		$index['loadset']=Params::where('code','LoadSetting')->pluck('label','id');
		// dd($bookings->meta()->get());
		// dd($bookings->meta()->get());
		// dd($index['bookings']->first());
		// dd($index);
		return view("reports.booking", $index);
	}

		// public function booking_post(Request $request) {
		
		
		// 	$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));
	
		// 	$y = array();
		// 	foreach ($years as $year) {
		// 		$y[$year->years] = $year->years;
		// 	}
		// 	$data['vehicle_select'] = $request->get('vehicle_id');
		// 	$data['customer_select'] = $request->get('customer_id');
		// 	$data['years'] = $y;
		// 	$data['year_select'] = $request->get("year");
		// 	$data['month_select'] = $request->get("month");
		// 	if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
		// 		$data['vehicles'] = VehicleModel::get();
		// 	} else {
		// 		$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		// 	}
		// 	$vehicle_ids = array(0);
		// 	foreach ($data['vehicles'] as $vehicle) {
		// 		$vehicle_ids[] = $vehicle->id;
		// 	}
		// 	$data['bookings'] = Bookings::whereYear("pickup", $data['year_select'])->whereMonth("pickup", $data['month_select'])->whereIn('vehicle_id', $vehicle_ids);
		// 	if ($request->get("vehicle_id") != "") {
		// 		$data['bookings'] = $data['bookings']->where("vehicle_id", $request->get("vehicle_id"));
		// 	}
		// 	if ($request->get("customer_id") != "") {
		// 		$data['bookings'] = $data['bookings']->where("customer_id", $request->get("customer_id"));
		// 	}
		// 	$data['bookings'] = $data['bookings']->get();
		
		// 	return view("reports.booking", $data);
		// }

	// public function booking_post(Request $request) {
		
		
	// 	$years = collect(DB::select("select distinct year(pickup) as years from bookings where deleted_at is null and pickup is not null order by years desc"));

	// 	$y = array();
	// 	$data['customers'] = User::where('user_type', 'C')->get();
	// 	foreach ($years as $year) {
	// 		$y[$year->years] = $year->years;
	// 	}
	// 	$data['vehicle_select'] = $request->get('vehicle_id');
	// 	$data['customer_select'] = $request->get('customer_id');
	// 	$data['years'] = $y;
	// 	$data['year_select'] = $request->get("year");
	// 	$data['month_select'] = $request->get("month");
	// 	if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
	// 		$data['vehicles'] = VehicleModel::get();
	// 	} else {
	// 		$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
	// 	}
	// 	// dd($data);
	// 	// $vehicle_ids = [];
	// 	foreach ($data['vehicles'] as $vehicle) {
	// 		$vehicle_ids[] = $vehicle->id;
	// 	}

	// 	if(empty($data['year_select']) && !empty($data['month_select']))
	// 		$data['bookings'] = Bookings::whereMonth("pickup", $data['month_select'])->whereIn('vehicle_id', $vehicle_ids);
	// 	elseif(!empty($data['year_select']) && empty($data['month_select']))
	// 		$data['bookings'] = Bookings::whereYear("pickup", $data['year_select'])->whereIn('vehicle_id', $vehicle_ids);
	// 	elseif(!empty($data['year_select']) && !empty($data['month_select']))
	// 		$data['bookings'] = Bookings::whereYear("pickup", $data['year_select'])->whereMonth("pickup", $data['month_select'])->whereIn('vehicle_id', $vehicle_ids);
	// 	else
	// 		$data['bookings'] = Bookings::whereIn('vehicle_id', $vehicle_ids);
	// 		//dd($data['bookings']->get());
		
	// 	if (!empty($request->get("vehicle_id")) && !empty($request->get("customer_id"))) {
	// 		$data['bookings'] = $data['bookings']->where(["vehicle_id"=>$request->get("vehicle_id"),"customer_id"=>$request->get("customer_id")]);
	// 	}
	// 	elseif (empty($request->get("vehicle_id")) && !empty($request->get("customer_id")) ) {
	// 		$data['bookings'] = $data['bookings']->where("customer_id", $request->get("customer_id"));
	// 	}
	// 	elseif (!empty($request->get("vehicle_id")) && empty($request->get("customer_id")) ) {
	// 		$data['bookings'] = $data['bookings']->where("vehicle_id", $request->get("vehicle_id"));
	// 	}
		
	// 	$bookList = $data['bookings']->get();
	// 	$bookListCust = $data['bookings']->groupBy('customer_id')->get();
	// 	foreach($bookListCust as $book){ //Customer Loop 
	// 		//dd($vehicle_ids);
	// 		$advpay_array = [];
	// 		$payment_amount = [];
	// 		$total_price = [];
			
	// 		$custget = $bookList->where('customer_id',$book->customer_id);
			
	// 		foreach($custget as $bc){ // Particular Cusomter booking
				
	// 			$advpay_array[] = floatval($bc->getMeta('advance_pay'));
	// 			$payment_amount[] = floatval($bc->getMeta('payment_amount'));
	// 			$total_price[] = floatval($bc->getMeta('total_price'));
	// 		}
	// 		$book->advpay_array = array_sum($advpay_array);
	// 		$book->payment_amount = array_sum($payment_amount);
	// 		$book->total_price = array_sum($total_price);
			
	// 	}
	// 	$data['result']="";
	// 	$data['bookings'] = $bookListCust;
	// 	//$data['customer_name'] = DB::table('users')->select('name')->where('id','=',$request->customer_id)->first()->name;
	// 	//dd($data);
	// 	return view("reports.booking", $data);
	// }

	public function view_booking_details($arr)
	{
		//dd('xxxxxx');
		$arr = explode(",",$arr);
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
		

		if(empty($arr[3]) && !empty($arr[2]))
			$data['bookings'] = Bookings::whereMonth("pickup", $arr[2])->whereIn('vehicle_id', $vehicle_ids);
		elseif(!empty($arr[3]) && empty($arr[2]))
			$data['bookings'] = Bookings::whereYear("pickup", $arr[3])->whereIn('vehicle_id', $vehicle_ids);
		elseif(!empty($arr[3]) && !empty($arr[2]))
			$data['bookings'] = Bookings::whereYear("pickup", $arr[3])->whereMonth("pickup", $arr[2])->whereIn('vehicle_id', $vehicle_ids);
		else
			$data['bookings'] = Bookings::whereIn('vehicle_id', $vehicle_ids);
			//dd($data['bookings']->get());
		
		if (!empty($arr[1]) && !empty($arr[0])) {
			$data['bookings'] = $data['bookings']->where(["vehicle_id"=>$arr[1],"customer_id"=>$arr[0]]);
		}
		elseif (empty($arr[1]) && !empty($arr[0]) ) {
			$data['bookings'] = $data['bookings']->where("customer_id", $arr[0]);
		}
		elseif (!empty($arr[1]) && empty($arr[0]) ) {
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
		

		if(empty($request->year) && !empty($request->month))
			$data['bookings'] = Bookings::whereMonth("pickup", $request->month)->whereIn('vehicle_id', $vehicle_ids);
		elseif(!empty($request->year) && empty($request->month))
			$data['bookings'] = Bookings::whereYear("pickup", $request->year)->whereIn('vehicle_id', $vehicle_ids);
		elseif(!empty($request->year) && !empty($request->month))
			$data['bookings'] = Bookings::whereYear("pickup", $request->year)->whereMonth("pickup", $request->month)->whereIn('vehicle_id', $vehicle_ids);
		else 
			$data['bookings'] = Bookings::whereIn('vehicle_id', $vehicle_ids);
			//dd($data['bookings']->get());
		
		if (!empty($request->vehicle_id) && !empty($request->customer_id)) {
			$data['bookings'] = $data['bookings']->where(["vehicle_id"=>$request->vehicle_id,"customer_id"=>$request->customer_id]);
		}
		elseif (empty($request->vehicle_id) && !empty($request->customer_id) ) {
			$data['bookings'] = $data['bookings']->where("customer_id", $request->customer_id);
		}
		elseif (!empty($request->vehicle_id) && empty($request->customer_id) ) {
			$data['bookings'] = $data['bookings']->where("vehicle_id", $request->vehicle_id);
		}
		$bookList = $data['bookings']->get();
		$data['bookings'] = $bookList;
		//$bookListCust = $data['bookings']->groupBy('customer_id')->get();
		return view("reports.view_booking_details_print", $data);
	}
	public function delinquent_post(Request $request) {

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

	public function monthly_post(Request $request) {

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

	public function fuel() {
		// dd($_POST);
		
		$data['vehicles'] = VehicleModel::select(
            DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
            ->pluck('vehicle_name', 'id');
		// dd(FuelType::pluck('fuel_name','id'));
		// dd($data['fuel']);
		$data['vehicle_id'] = "";
		$data['fuel_types'] = FuelType::pluck('fuel_name','id');
		$data['year_select'] = date("Y");
		$data['month_select'] = date("n");
		$data['months'] = Helper::getMonths(true);
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;
		// dd($data);
		return view('reports.fuel', $data);
	}

	public function fuel_post(Request $request) {
		
		// dd($request->all());

		$data['vehicles'] = VehicleModel::select(
            DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
            ->pluck('vehicle_name', 'id');

		$vehicle_id = $request->get('vehicle_id');
		$fuel_type = $request->get('fuel_type');
		$data['vehicle_id'] = $vehicle_id;
		$data['vehicle_id'] = $vehicle_id;
		if($request->get('date1')==null)
			$start = FuelModel::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = FuelModel::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			
		// dd($start);
		// dd($end);
		

		
		if (!empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where(['vehicle_id'=>$vehicle_id,'fuel_type'=>$fuel_type])->whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}else if (empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('fuel_type',$fuel_type)->whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}else if (!empty($vehicle_id) && empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('vehicle_id',$vehicle_id)->whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}else {
			$data['fuel'] = FuelModel::whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}

		
		foreach($data['fuel'] as $f){
			$f->total = empty($f->total) ? $f->qty * $f->cost_per_unit : $f->total;
			$f->gtotal = empty($f->grand_total) ? $f->total : $f->grand_total;
		}

		// Total KM
		if (!empty($vehicle_id)) {
			$bookingdata = Bookings::where('vehicle_id',$vehicle_id)->whereBetween('pickup', [$start, $end])->get();
		}
		else {
			$bookingdata = Bookings::whereBetween('pickup', [$start, $end])->get();
		}
		$total_km=[];
		foreach($bookingdata as $bd){
			$total_km[] = Helper::removeKM($bd->getMeta('distance'));
		}

		// Helper::totalBookingIncome($vehicle_id);
		// $data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])->whereBetween('created_at', [$start, $end])->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();
		// dd();
		$data['fuel_types'] = FuelType::pluck('fuel_name','id');
		$data['fuel_totalprice'] = $data['fuel']->sum('total');
		$data['fuel_totalqty'] = $data['fuel']->sum('qty');
		$data['total_km'] = array_sum($total_km);
		$data['result'] = "";
		$data['request'] = $request->all();
		$data['months'] = Helper::getMonths(true);
		$data['dates'] = [$start,$end];
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('reports.fuel', $data);
	}

	public function yearly() {
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

	public function yearly_post(Request $request) {

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

	public function vendors() {

		if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
			$data['vehicles'] = VehicleModel::get();
		} else {
			$data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->get();
		}
		$vehicle_ids = array(0);
		foreach ($data['vehicles'] as $vehicle) {
			$vehicle_ids[] = $vehicle->id;
		}
		$details_parts= PartsModel::select(['vendor_id',DB::raw('sum(unit_cost) as total')])->groupBy('vendor_id');
		// dd($details_parts);
		$details_fuel= FuelModel::select(['vendor_name',DB::raw('sum(qty*cost_per_unit) as total')])->whereNotNull('vendor_name')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_name');
		//dd($details_fuel);
		$data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])
		->where('vendor_id','<>',7)
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
			 ->where('vendor_id','<>',7)
			 ->groupBy('vendor_id')->union($kkg)->union($kkgd)->get();
		
		$b = array(); 
		foreach ($kk as $k) {
			$b[$k->vendor_id] = $k->vendor->name;

		}
		$combineArray=[];
		// dd($data);
		foreach($data['details'] as $value){
			// dd($value);
			if(Vendor::withTrashed()->where('id',$value->vendor_id)->first('name')->exists())
				$vendorName =Vendor::withTrashed()->where('id',$value->vendor_id)->first('name')->name;
			else
				$vendorName ='N/A';
				
			if(empty($combineArray[$value->vendor_id])){
				$combineArray[$value->vendor_id]['name']=$vendorName;
				$combineArray[$value->vendor_id]['total']=$value->total;
			}else{
				// dd($value->vendor_id);
				$combineArray[$value->vendor_id]['name']=$vendorName;
				$combineArray[$value->vendor_id]['total']=$combineArray[$value->vendor_id]['total']+$value->total;
			}
			
		}

		$data['vendors'] = $b;
		$data['graph'] =collect($combineArray);
		$data['date1'] = null;
		$data['date2'] = null;
		// dd($data);
		return view('reports.vendor', $data);
	}

	public function vendors_post(Request $request) {
 
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
		
		$details_parts= PartsModel::select(['vendor_id',DB::raw('sum(unit_cost) as total')])->groupBy('vendor_id');
		
		$details_fuel= FuelModel::select(['vendor_name',DB::raw('sum(qty*cost_per_unit) as total')])->whereNotNull('vendor_name')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_name');
		
		$data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])
		->whereBetween('created_at', [$start, $end])
		->whereIn('vehicle_id', $vehicle_ids)
		->where('vendor_id','<>',7)
		->groupBy('vendor_id')->union($details_fuel)
		->union($details_parts)->get();
		
		$kkgd = PartsModel::select('vendor_id')->whereNotNull('vendor_id')->groupBy('vendor_id');
		$kkg = FuelModel::select('vendor_name')->whereNotNull('vendor_name')->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_name');
		$kk = WorkOrders::select('vendor_id')
		->whereIn('vehicle_id', $vehicle_ids)
		->where('vendor_id','<>',7)
		->groupBy('vendor_id')
		->union($kkg)->union($kkgd)->get();
		
		$b = array(); 
		foreach ($kk as $k) {
			$b[$k->vendor_id] = $k->vendor->name;

		}
		
		$combineArray=[];
		foreach($data['details'] as $value){
		
			if(Vendor::where('id',$value->vendor_id)->first('name')->exists())
				$vendorName =Vendor::where('id',$value->vendor_id)->first('name')->name;
			else
				$vendorName ='N/A';
				
			if(empty($combineArray[$value->vendor_id])){
				$combineArray[$value->vendor_id]['name']=$vendorName;
				$combineArray[$value->vendor_id]['total']=$value->total;
			}else{
			
				$combineArray[$value->vendor_id]['name']=$vendorName;
				$combineArray[$value->vendor_id]['total']=$combineArray[$value->vendor_id]['total']+$value->total;
			}
			
		}

		$data['vendors'] = $b;
		$data['date1'] = $request->date1;
		$data['date2'] = $request->date2;
		$data['graph'] =collect($combineArray);
		return view('reports.vendor', $data);

	}

	public function drivers() {

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

	public function drivers_post(Request $request) {
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

	public function customers() {
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

	public function customers_post(Request $request) {
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

	public function print_deliquent(Request $request) {
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

	public function print_monthly(Request $request) {
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

	public function print_booking(Request $request) {
		$customer_id = $request->customer_id;
		$vehicle_id = $request->vehicle_id;
		$from_date = $request->date1;
		$to_date = $request->date2;
		$from_date = empty($from_date) ? Bookings::orderBy('pickup','asc')->take(1)->first('pickup')->pickup : $from_date;
		$to_date = empty($to_date) ? Bookings::orderBy('pickup','desc')->take(1)->first('pickup')->pickup : $to_date;
		// $abc['vendor_id'] = $vendor_id;
		// $abc['fuel_type'] = $fuel_type;
		// $abc['from_date'] = $from_date;
		// $abc['to_date'] = $to_date;
		// dd($abc);
		//same date search
		if(strtotime($from_date)==strtotime($to_date)){
			$from_date = $from_date." 00:00:00";
			$to_date = $to_date." 23:59:59";
		}
		if(empty($vehicle_id) && empty($customer_id))
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date]);
		elseif(empty($vehicle_id))
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date])->where('customer_id',$customer_id);
		elseif(empty($customer_id))
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date])->where('vehicle_id',$vehicle_id);
		else
			$bookings = Bookings::whereBetween('pickup',[$from_date,$to_date])->where(['vehicle_id'=>$vehicle_id,'customer_id'=>$customer_id]);
		$total = array();
		foreach($bookings->get() as $bk){
			$total[] = $bk->getMeta('total_price');
			$totalfuel[] = $bk->getMeta('pet_required');
			$totaldistance[] = !empty($bk->getMeta('distance')) ? explode(" ",$bk->getMeta('distance'))[0] : 0;
		}

		$index['vehicles'] = VehicleModel::select(
			DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
			->pluck('vehicle_name', 'id');

		$index['customers'] = User::where('user_type', 'C')->pluck("name","id");
		$index['bookings']=$bookings->orderBy('id','DESC')->get();
		$index['result']='';
		$index['date1'] = null;
		$index['date2'] = null;
		$index['total_price']=array_sum($total);
		$index['total_fuel']=array_sum($totalfuel);
		$index['total_distance']=array_sum($totaldistance);
		$index['request']=$request->all();
		$index['loadset']=Params::where('code','LoadSetting')->pluck('label','id');
		// dd($index);
		return view('reports.print_bookings', $index);
		
	}

	public function print_fuel(Request $request) {
		// dd($request->all());

		$vehicle_id = $request->get('vehicle_id');
		$fuel_type = $request->get('fuel_type');
		$data['vehicle_id'] = $vehicle_id;
		$data['vehicle_id'] = $vehicle_id;
		if($request->get('date1')==null)
			$start = FuelModel::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = FuelModel::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			
		// dd($start);
		// dd($end);
		

		
		if (!empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where(['vehicle_id'=>$vehicle_id,'fuel_type'=>$fuel_type])->whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}else if (empty($vehicle_id) && !empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('fuel_type',$fuel_type)->whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}else if (!empty($vehicle_id) && empty($fuel_type)) {
			$data['fuel'] = FuelModel::where('vehicle_id',$vehicle_id)->whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}else {
			$data['fuel'] = FuelModel::whereBetween('date', [$start, $end])->orderBy("id","DESC")->get();
		}

		
		foreach($data['fuel'] as $f){
			$f->total = empty($f->total) ? $f->qty * $f->cost_per_unit : $f->total;
			$f->gtotal = empty($f->grand_total) ? $f->total : $f->grand_total;
		}

		// Total KM
		if (!empty($vehicle_id)) {
			$bookingdata = Bookings::where('vehicle_id',$vehicle_id)->whereBetween('pickup', [$start, $end])->get();
		}
		else {
			$bookingdata = Bookings::whereBetween('pickup', [$start, $end])->get();
		}
		$total_km=[];
		foreach($bookingdata as $bd){
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

	public function print_yearly(Request $request) {

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

	public function print_driver(Request $request) {
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

	public function print_vendor() {
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

	public function print_customer(Request $request) {
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

	public function users() {
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

	public function users_post(Request $request) {
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

	public function print_users(Request $request) {

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

	public function trashSearch(){
		// dd("search");
		// dd(Hash::make('password'));
        $index['from'] = Params::where('code','PaymentFrom')->pluck('label','id');
		$index['advance'] = Params::where('code','AdvanceFor')->pluck('label','id');
		$index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
		$index['type'] = Params::where('code','PaymentType')->pluck('label','id');
		$index['is_complete'] = [1=>'Completed',2=>'In Progress',3=>'Not Assigned'];
		$index['order_by'] = ['id'=>'Transaction ID','total'=>'Amount'];
		$index['sort'] = [2=>'Descending',1=>'Ascending'];
		$index['bank'] = BankAccount::pluck('bank','id');
		$index['advance_for'] = Params::where('code','AdvanceFor')->pluck('label','id');
		$index['request'] = null;
		// dd($index);
		return view('reports.transaction',$index);
	}

	public function trashSearch_post(Request $request){
		// dd($request->all());
		$transaction_id = $request->transaction_id;
		$from = $request->from;
		$type = $request->type;
		$is_complete = $request->is_complete;
		$bank = $request->bank;
		$advance_for = $request->advance_for;
		$order_by = $request->order_by;
		$sort = $request->sort==1 ? "ASC" : "DESC";
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at: $from_date;
		$to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at: $to_date;

		if($request->has('transaction_id') && !empty($transaction_id)){
			if(!empty($advance_for) && Helper::string_exists("BO",$transaction_id))
				$tr = Transaction::where("transaction_id",$transaction_id)->where('advance_for',$advance_for);
			else
				$tr = Transaction::where("transaction_id",$transaction_id);
			$tr = $tr->get();
		}else{
			$whr=array();
			if(!empty($from)){
				$fromStr = " param_id ='$from' ";	
				array_push($whr,$fromStr);
			}
			if(!empty($type)){
				$typeStr = " type ='$type' ";	
				array_push($whr,$typeStr);
			} 
			if(!empty($is_complete)){
				if(in_array($is_complete,[1,2])){
					$isComStr = " is_completed = '$is_complete'";
				}elseif($is_complete==3)
					$isComStr = " is_completed IS NULL ";
				array_push($whr,$isComStr);
			}
			if(!empty($advance_for) && !empty($from) && $from==18){
				$advanceForStr = " advance_for ='$advance_for' ";	
				array_push($whr,$advanceForStr);
			}
			if(!empty($bank)){
				$bankStr = " bank_id ='$bank' ";	
				array_push($whr,$bankStr);
			} 
			 
			if(!empty($from_date) && !empty($to_date)){
				$dateBetStr = " (created_at BETWEEN '$from_date' AND '$to_date') ";	
				array_push($whr,$dateBetStr);
			} 
			$where = implode(' AND ',$whr);

			$orderBy = "ORDER BY $order_by $sort";

			$qq = "select * from transactions where $where $orderBy";
			// dd($qq);
			$tr = Transaction::whereRaw($where)->orderBy($order_by,$sort)->get();
		}
		// dd($tr);
		$index['transaction'] = $tr;
		$index['totalTransaction'] = $index['transaction']->sum('total');
		// dd($qq);
		$index['from'] = Params::where('code','PaymentFrom')->pluck('label','id');
		$index['advance'] = Params::where('code','AdvanceFor')->pluck('label','id');
		$index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
		$index['type'] = Params::where('code','PaymentType')->pluck('label','id');
		$index['is_complete'] = [1=>'Completed',2=>'In Progress',3=>'Not Assigned'];
		$index['order_by'] = ['id'=>'Transaction ID','total'=>'Amount'];
		$index['sort'] = [2=>'Descending',1=>'Ascending'];
		$index['bank'] = BankAccount::pluck('bank','id');
		$index['advance_for'] = Params::where('code','AdvanceFor')->pluck('label','id');
		$index['result']='';
		$index['request']=$request->all();
		// dd($index);
		return view('reports.transaction',$index);
	}

	public function trashSearch_print(Request $request){
		$transaction_id = $request->transaction_id;
		$from = $request->from;
		$type = $request->type;
		$is_complete = $request->is_complete;
		$bank = $request->bank;
		$advance_for = $request->advance_for;
		$order_by = $request->order_by;
		$sort = $request->sort==1 ? "ASC" : "DESC";
		$from_date = $request->from_date;
		$to_date = $request->to_date;
		$from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at: $from_date;
		$to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at: $to_date;

		if($request->has('transaction_id') && !empty($transaction_id)){
			if(!empty($advance_for) && Helper::string_exists("BO",$transaction_id))
				$tr = Transaction::where("transaction_id",$transaction_id)->where('advance_for',$advance_for);
			else
				$tr = Transaction::where("transaction_id",$transaction_id);
			$from = $tr->first()->from;
			$tr = $tr->get();
		}else{
			$whr=array();
			if(!empty($from)){
				$fromStr = " param_id ='$from' ";	
				array_push($whr,$fromStr);
			}
			if(!empty($type)){
				$typeStr = " type ='$type' ";	
				array_push($whr,$typeStr);
			} 
			if(!empty($is_complete)){
				if(in_array($is_complete,[1,2])){
					$isComStr = " is_completed = '$is_complete'";
				}elseif($is_complete==3)
					$isComStr = " is_completed IS NULL ";
				array_push($whr,$isComStr);
			}
			if(!empty($advance_for) && !empty($from) && $from==18){
				$advanceForStr = " advance_for ='$advance_for' ";	
				array_push($whr,$advanceForStr);
			}
			if(!empty($bank)){
				$bankStr = " bank_id ='$bank' ";	
				array_push($whr,$bankStr);
			} 
			 
			if(!empty($from_date) && !empty($to_date)){
				$dateBetStr = " (created_at BETWEEN '$from_date' AND '$to_date') ";	
				array_push($whr,$dateBetStr);
			} 
			$where = implode(' AND ',$whr);

			$orderBy = "ORDER BY $order_by $sort";

			$qq = "select * from transactions where $where $orderBy";
			// dd($qq);
			$tr = Transaction::whereRaw($where)->orderBy($order_by,$sort)->get();
		}
		// dd($tr);
		foreach($tr as $r){
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
		$index['date'] = ['from_date'=>$from_date,'to_date'=>$to_date];
		$index['request']=$request->all();
		// dd($request->all());

		// dd($index);
		return view('reports.print_transaction',$index);
	}

	public function trashSearchBank(){
		dd("Bank Transact search");
	}

	public function trashSearchBank_post(){

	}

	public function trashSearchBank_print(){

	}

	public function driverspayroll(){
		// dd("Drivers Report");
		$data['drivers'] = User::where('user_type','D')->pluck('name','id');
		$data['request'] = null;
		return view('reports.driver-report',$data);
	}

	public function driverspayroll_post(Request $request){
		// dd("Drivers Post Report");
		// dd($request->all());
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type','D')->pluck('name','id');
		if($request->get('date1')==null)
			$start = Payroll::orderBy('for_date','ASC')->take(1)->first('for_date')->for_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Payroll::orderBy('for_date','DESC')->take(1)->first('for_date')->for_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			// dd($data);
		if(!empty($driver_id))
			$data['payroll'] = Payroll::where('user_id',$driver_id)->whereBetween('for_date',[$start,$end])->get();
		else
			$data['payroll'] = Payroll::whereBetween('for_date',[$start,$end])->get();
			// dd($data['payroll']);
			
			$data['total_salary'] = $data['payroll']->sum('salary');
			$data['payable_salary'] = $data['payroll']->sum('payable_salary');
			$data['advance'] = $data['total_salary'] -$data['payable_salary'];
			
		foreach($data['payroll'] as $py){
			$vehicle = $py->driver_vehicle;
			// dd($vehicle);
			if(!empty($vehicle) && !empty($vehicle->vehicle)){
				$v = $vehicle->vehicle;
				$py->vehicle_det = $v->make ."-". $v->model . "-". $v->license_plate; 
			}
			else
				$py->vehicle_det = "N/A";
		}
		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['request'] = $request->all();
		// dd($data);
		return view('reports.driver-report',$data);

	}
	public function driverspayroll_print(Request $request){
		// dd($request->all());
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type','D')->pluck('name','id');
		if($request->get('date1')==null)
			$start = Payroll::orderBy('for_date','ASC')->take(1)->first('for_date')->for_date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Payroll::orderBy('for_date','DESC')->take(1)->first('for_date')->for_date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			// dd($data);
		if(!empty($driver_id))
			$data['payroll'] = Payroll::where('user_id',$driver_id)->whereBetween('for_date',[$start,$end])->get();
		else
			$data['payroll'] = Payroll::whereBetween('for_date',[$start,$end])->get();
			// dd($data['payroll']);
			
			$data['total_salary'] = $data['payroll']->sum('salary');
			$data['payable_salary'] = $data['payroll']->sum('payable_salary');
			$data['advance'] = $data['total_salary'] -$data['payable_salary'];
			
		foreach($data['payroll'] as $py){
			$vehicle = $py->driver_vehicle;
			// dd($vehicle);
			if(!empty($vehicle) && !empty($vehicle->vehicle) && empty($driver_id)){
				$v = $vehicle->vehicle;
				$py->vehicle_det = $v->make ."-". $v->model . "-". $v->license_plate; 
			}
			else
				$py->vehicle_det = "N/A";
		}
		//Selected Driver Name & Vehicle
		if(!empty($driver_id)){
			$drmodel = DriverVehicleModel::where('driver_id',$driver_id)->first();
			$data['vehicleData'] = !empty($drmodel) ? $drmodel->vehicle : null;
		}else $data['vehicleData'] = null;
		$data['result'] = "";
		$data['driver_name'] = !empty($driver_id) ? User::find($driver_id)->name : null;
		$data['dates'] = [$start,$end];
		$data['from_date'] = Helper::getCanonicalDate($start);
		$data['to_date'] = Helper::getCanonicalDate($end);
		// dd($data);
		return view('reports.print-driver-report',$data);
		
	}

	public function driversAdvance(){
		// dd('Driver Advance');
		// dd("Drivers Report");
		$data['drivers'] = User::where('user_type','D')->pluck('name','id');
		$data['request'] = null;
		return view('reports.driveradvance',$data);
	}

	public function driversAdvance_post(Request $request){
		// dd("Post");
		// dd("Drivers Post Report");
		// dd($request->all());
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type','D')->pluck('name','id');
		if($request->get('date1')==null)
			$start = Bookings::orderBy('pickup','ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Bookings::orderBy('pickup','DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d',strtotime($start));
		$end = date('Y-m-d',strtotime($end));
		
		

		$bookData = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'advance_pay')
						->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
				})->whereBetween(DB::raw('DATE(pickup)'), [$start, $end]);
		if(!empty($driver_id))
			$data['advance_bookings'] = $bookData->where('driver_id',$driver_id)->orderBy("id","DESC")->get();
		else
			$data['advance_bookings'] = $bookData->orderBy("id","DESC")->get();
		

		// dd($data);
			
			// $data['total_advance'] = $data['advance_bookings']->where('bookings_meta.key', '=', 'advance_pay')->sum('bookings_meta.value');
			// $data['payable_salary'] = $data['payroll']->sum('payable_salary');
			// $data['advance'] = $data['total_salary'] -$data['payable_salary'];
		if(empty($driver_id)){	
			foreach($data['advance_bookings'] as $adb){
					$v  = $adb->vehicle;
					$adb->vehicle_det = $v->make ."-". $v->model . "-". $v->license_plate;
			}
		}
		// dd($data['advance_bookings']->sum('advance_pay'));

		// Vehicle selected
		if(!empty($driver_id)){
			$drmodel = VehicleModel::find($driver_id);
			$data['vehicleData'] = !empty($drmodel) ? $drmodel->vehicle : null;
		}else $data['vehicleData'] = null;
		// dd($data);
		$data['total_advance'] = $data['advance_bookings']->sum('advance_pay');
		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['request'] = $request->all();
		// dd($data);
		return view('reports.driveradvance',$data);
	}

	public function driversAdvance_print(Request $request){
		$data['driver_id'] = $driver_id = $request->get('driver_id');
		$data['drivers'] = User::where('user_type','D')->pluck('name','id');
		if($request->get('date1')==null)
			$start = Bookings::orderBy('pickup','ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Bookings::orderBy('pickup','DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d',strtotime($start));
		$end = date('Y-m-d',strtotime($end));
		
		

		$bookData = Bookings::meta()
				->where(function ($query) {
					$query->where('bookings_meta.key', '=', 'advance_pay')
						->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
				})->whereBetween(DB::raw('DATE(pickup)'), [$start, $end]);
		if(!empty($driver_id))
			$data['advance_bookings'] = $bookData->where('driver_id',$driver_id)->orderBy("id","DESC")->get();
		else
			$data['advance_bookings'] = $bookData->orderBy("id","DESC")->get();
		

		// dd($data);
			
			// $data['total_advance'] = $data['advance_bookings']->where('bookings_meta.key', '=', 'advance_pay')->sum('bookings_meta.value');
			// $data['payable_salary'] = $data['payroll']->sum('payable_salary');
			// $data['advance'] = $data['total_salary'] -$data['payable_salary'];
		if(empty($driver_id)){	
			foreach($data['advance_bookings'] as $adb){
					$v  = $adb->vehicle;
					$adb->vehicle_det = $v->make ."-". $v->model . "-". $v->license_plate;
			}
		}
		// dd($data['advance_bookings']->sum('advance_pay'));

		// Vehicle selected
		if(!empty($driver_id)){
			$drmodel = VehicleModel::find($driver_id);
			$data['vehicleData'] = !empty($drmodel) ? $drmodel->vehicle : null;
		}else $data['vehicleData'] = null;
		// dd($data);
		$data['total_advance'] = $data['advance_bookings']->sum('advance_pay');
		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['request'] = $request->all();
		$data['driver_name'] = !empty($driver_id) ? User::find($driver_id)->name : null;
		$data['from_date'] = Helper::getCanonicalDate($start);
		$data['to_date'] = Helper::getCanonicalDate($end);
		// dd($data);
		return view('reports.print-driveradvance',$data);
	}
	public function statement(){
		$data['request'] = null;
		$data['from_date'] = null;
		$data['to_date'] = null;
		return view('reports.statement',$data);
	}

	public function statement_post(Request $request){
		// dd($request->all());
		$incomeExpense = IncomeExpense::orderBy("id","ASC")->get();
		// $transaction = Transaction::get();

		foreach($incomeExpense as $key=>$tr){
			// dd($tr,$tr->transaction_id,$tr->transaction);
			
			$trash = $tr->transaction;
			if(!$trash==null){
				//Booking,PartsInvoice,Fuel (Break and Continue)
				if(($trash->param_id == 18 || $trash->param_id == 20 || $trash->param_id == 26) && $tr->amount==0){
					$incomeExpense->forget($key);
					continue;
				}

				if($trash->param_id==18){ //bookings
					if($tr->amount==0 || $tr->amount==null){
						$shalom = Bookings::where('id',$trash->from_id);
						$tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
					}else{//for driver advance
						$tr->dateof = !empty($tr->date) ? $tr->date." 00:00:00" : null;
					}
				}else if($trash->param_id==19){//payroll
					$shalom = Payroll::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==20){//fuel
					if($tr->amount==0 || $tr->amount==null){
						$shalom = FuelModel::where('id',$trash->from_id);
						$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
					}else{
						$tr->dateof = !empty($tr->date) ? $tr->date : null;
					}
				}else if($trash->param_id==25){//salary advance
					$shalom = DailyAdvance::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==26){//parts invoice
					if($tr->amount==0 || $tr->amount==null){
						$shalom = PartsInvoice::where('id',$trash->from_id);
						$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
					}else{
						$tr->dateof = !empty($tr->date) ? $tr->date : null;
					}
				}else if($trash->param_id==27){//advance driver refund
					// $shalom = AdvanceDriver::where('id',$trash->from_id);
					// $tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
					$tr->dateof = $trash->created_at;
				}else if($trash->param_id==28){//work order
					$shalom = WorkOrders::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				}else if($trash->param_id==29){//starting amount
					$shalom = BankAccount::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->updated_at : null;
				}else if($trash->param_id==30){//deposit
					$shalom = BankTransaction::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==31){//revised
					$shalom = BankTransaction::where(['id'=>$trash->from_id,'from_id'=>!null]);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==32){//driver liability
					$shalom = DailyAdvance::where(['id'=>$trash->from_id,'from_id'=>!null]);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==35){//document renew
					$shalom = VehicleDocs::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				}else if($trash->param_id==43){//other advance
					$shalom = OtherAdvance::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==44){//advance refund
					$shalom = OtherAdvance::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}
				$tr->type = $trash->type;
			}
		}
		// dd($transaction->reverse()->toArray());
		// $filtered = $transaction;
		$filtered = $incomeExpense->where('dateof','!=',null)->flatten();
		// $filtered1 = $incomeExpense->where('dateof','=',null)->flatten();
		// dd($incomeExpense,$filtered,$filtered1);
		if($request->get('date1')==null)
			$start = !empty($filtered) ? $filtered->first()->dateof : null;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = !empty($filtered) ? $filtered->reverse()->first()->dateof : null;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d',strtotime($start));
		$end = date('Y-m-d',strtotime($end));
		// dd($filtered);
		// dd($filtered->get(count($filtered)-2));
		// dd($filtered->first(),$filtered->reverse()->first());
		// dd($start,$end);
		//Opening Balance and closing balance
		$openingCredit = $filtered->where('dateof','<',$start)->where('type',23)->sum('amount'); //DOUBT X
		$openingDebit = $filtered->where('dateof','<',$start)->where('type',24)->sum('amount');
		$openingBalance = $openingCredit-$openingDebit;
		// dd($openingCredit,$openingDebit,$openingBalance);

		$closingCredit = $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"])->where('type',23)->sum('amount');
		$closingDebit = $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"])->where('type',24)->sum('amount');
		$closingAmount = $closingCredit-$closingDebit;

		if($openingBalance==0){
			$closingBalance = $closingAmount;
		}else{
			$closingBalance = $openingBalance+$closingAmount;
		}

		// dd($openingBalance,$closingCredit,$closingDebit,$closingAmount,$closingBalance); //DOUBT X

		// $filtered = strtotime($start)==strtotime($end) ? $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"]) : $filtered->whereBetween('dateof',[$start,$end]);
		$filtered = $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"]);

		// dd($filtered);
		$data['transactions'] = $filtered->flatten();
		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['from_date'] = date("Y-m-d",strtotime($start));
		$data['to_date'] = date("Y-m-d",strtotime($end));
		$data['openingBalance'] = $openingBalance;
		$data['closingBalance'] = $closingBalance;
		$data['request'] = $request->all();
		// dd($data);
		// dd($data['transactions']->first());
		return view('reports.statement',$data);
	}

	public function statement_print(Request $request){
		// dd($request->all());
		$incomeExpense = IncomeExpense::orderBy("id","ASC")->get();
		// $transaction = Transaction::get();

		foreach($incomeExpense as $key=>$tr){
			// dd($tr,$tr->transaction_id,$tr->transaction);
			
			$trash = $tr->transaction;
			if(!$trash==null){
				//Booking,PartsInvoice,Fuel (Break and Continue)
				if(($trash->param_id == 18 || $trash->param_id == 20 || $trash->param_id == 26) && $tr->amount==0){
					$incomeExpense->forget($key);
					continue;
				}

				if($trash->param_id==18){ //bookings
					if($tr->amount==0 || $tr->amount==null){
						$shalom = Bookings::where('id',$trash->from_id);
						$tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
					}else{//for driver advance
						$tr->dateof = !empty($tr->date) ? $tr->date." 00:00:00" : null;
					}
				}else if($trash->param_id==19){//payroll
					$shalom = Payroll::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==20){//fuel
					if($tr->amount==0 || $tr->amount==null){
						$shalom = FuelModel::where('id',$trash->from_id);
						$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
					}else{
						$tr->dateof = !empty($tr->date) ? $tr->date : null;
					}
				}else if($trash->param_id==25){//salary advance
					$shalom = DailyAdvance::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==26){//parts invoice
					if($tr->amount==0 || $tr->amount==null){
						$shalom = PartsInvoice::where('id',$trash->from_id);
						$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
					}else{
						$tr->dateof = !empty($tr->date) ? $tr->date : null;
					}
				}else if($trash->param_id==27){//advance driver refund
					// $shalom = AdvanceDriver::where('id',$trash->from_id);
					// $tr->dateof = $shalom->exists() ? $shalom->first()->pickup : null;
					$tr->dateof = $trash->created_at;
				}else if($trash->param_id==28){//work order
					$shalom = WorkOrders::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				}else if($trash->param_id==29){//starting amount
					$shalom = BankAccount::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->updated_at : null;
				}else if($trash->param_id==30){//deposit
					$shalom = BankTransaction::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==31){//revised
					$shalom = BankTransaction::where(['id'=>$trash->from_id,'from_id'=>!null]);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==32){//driver liability
					$shalom = DailyAdvance::where(['id'=>$trash->from_id,'from_id'=>!null]);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==35){//document renew
					$shalom = VehicleDocs::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->created_at : null;
				}else if($trash->param_id==43){//other advance
					$shalom = OtherAdvance::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}else if($trash->param_id==44){//advance refund
					$shalom = OtherAdvance::where('id',$trash->from_id);
					$tr->dateof = $shalom->exists() ? $shalom->first()->date : null;
				}
				$tr->type = $trash->type;
			}
		}
		// dd($transaction->reverse()->toArray());
		// $filtered = $transaction;
		$filtered = $incomeExpense->where('dateof','!=',null)->flatten();
		// $filtered1 = $incomeExpense->where('dateof','=',null)->flatten();
		// dd($incomeExpense,$filtered,$filtered1);
		if($request->get('date1')==null)
			$start = !empty($filtered) ? $filtered->first()->dateof : null;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = !empty($filtered) ? $filtered->reverse()->first()->dateof : null;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

		$start = date('Y-m-d',strtotime($start));
		$end = date('Y-m-d',strtotime($end));
		// dd($filtered);
		// dd($filtered->get(count($filtered)-2));
		// dd($filtered->first(),$filtered->reverse()->first());
		// dd($start,$end);
		//Opening Balance and closing balance
		$openingCredit = $filtered->where('dateof','<',$start)->where('type',23)->sum('amount'); //DOUBT X
		$openingDebit = $filtered->where('dateof','<',$start)->where('type',24)->sum('amount');
		$openingBalance = $openingCredit-$openingDebit;
		// dd($openingCredit,$openingDebit,$openingBalance);

		$closingCredit = $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"])->where('type',23)->sum('amount');
		$closingDebit = $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"])->where('type',24)->sum('amount');
		$closingAmount = $closingCredit-$closingDebit;

		if($openingBalance==0){
			$closingBalance = $closingAmount;
		}else{
			$closingBalance = $openingBalance+$closingAmount;
		}

		// dd($openingBalance,$closingCredit,$closingDebit,$closingAmount,$closingBalance); //DOUBT X/

		// $filtered = strtotime($start)==strtotime($end) ? $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"]) : $filtered->whereBetween('dateof',[$start,$end]);
		$filtered = $filtered->whereBetween('dateof',["$start 00:00:00","$end 23:59:59"]);

		// dd($filtered);
		$data['transactions'] = $filtered->flatten();
		$data['result'] = "";
		$data['dates'] = [$start,$end];
		$data['from_date'] = date("Y-m-d",strtotime($start));
		$data['to_date'] = date("Y-m-d",strtotime($end));
		$data['openingBalance'] = $openingBalance;
		$data['closingBalance'] = $closingBalance;
		$data['closingAmount'] = $closingAmount;
		$data['request'] = $request->all();
		// dd($data);
		return view('reports.statement-report',$data);
	}
	
}
