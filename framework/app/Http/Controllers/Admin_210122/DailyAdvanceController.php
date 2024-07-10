<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\AdvanceDriver;
use App\Model\DailyAdvance;
use App\Model\User;
use App\Model\Params;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\Payroll;
use App\Model\BankAccount;
use App\Model\BankTransaction;
use App\Model\Bookings;
use DB;
use Illuminate\Http\Request;
use Helper;

class DailyAdvanceController extends Controller
{
    public function index()
    {
       /* //Updating previous advance to driver details to transaction
        $advances = AdvanceDriver::where('param_id', 7)->whereRaw("value IS NOT NULL")->get();
        // dd($advances);
        foreach ($advances as $advance) {
            $driver_id = Bookings::find($advance->booking_id)->driver_id;
            $pick_date = date("Y-m-d", strtotime(Bookings::find($advance->booking_id)->pickup));
            $array = ['driver_id' => $driver_id, 'date' => $pick_date, 'amount' => $advance->value, 'remarks' => $advance->remarks];
            // dd($array);
            $dailyid = DailyAdvance::create($array)->id;

            // dd($array);

            // Accounting
            if (!empty($advance->value)) {
                $account['from_id'] = $dailyid; //daily advance id
                $account['type'] = 24; //Debit 
                $account['bank_id'] = 1; //SELF CASH Bank Account
                $account['param_id'] = 25; //From Daily Advance
                $account['advance_for'] = 21; //Driver Advance in Daily Advance
                $account['total'] = bcdiv($advance->value, 1, 2);


                $transid = Transaction::create($account)->id;
                $trash = ['type' => 24, 'from' => 25, 'id' => $transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                $income['transaction_id'] = $transid;
                $income['payment_method'] = 17;
                $income['date'] = $pick_date;
                $income['amount'] = bcdiv($advance->value, 1, 2);
                $income['remaining'] = 0;
                $income['remarks'] = $advance->remarks;

                IncomeExpense::create($income);
            }
        }
*/

        $index['dailys'] = DailyAdvance::orderBy('id','DESC')->get();
        foreach($index['dailys'] as $d){
            $trash = Transaction::where(['from_id'=>$d->id,'param_id'=>25]);
            $d->is_transaction = $trash->exists() ? true :false;
        }
        // dd($index);
        return view('daily_advance.index',$index);
    }

    public function create()
    {
        $index['driver'] = User::whereUser_type('D')->pluck('name','id');
        $index['purse'] = $this->dough();
        // dd($index);
        return view('daily_advance.create',$index);
    }

    public function dough(){
        $startingAmount = BankAccount::find(1)->starting_amount;
        $deposits = $index['deposits'] = BankTransaction::where('bank_id',1)->sum('amount');
        $total_dough = (float) $startingAmount +  (float) $deposits;
        $spent = $index['spent'] = Transaction::where(['bank_id'=>1,'type'=>24])->sum('total');
        $rem = $total_dough - $spent;
        return $rem;
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        $driver_ids = $request->driver_id;
        if(count($driver_ids)>0){
            foreach($driver_ids as $id){
                $array = ['driver_id'=>$id,'date'=>$request->date,'amount'=>$request->amount,'remarks'=>$request->remarks[$id]];
                // dd($array);
                $dailyid = DailyAdvance::create($array)->id;
            
                // dd($array);

                // Accounting
                if(!empty($request->amount)){
                    $account['from_id'] = $dailyid; //daily advance id
                    $account['type'] = 24; //Debit 
                    $account['bank_id'] = 1; //SELF CASH Bank Account
                    $account['param_id'] = 25; //From Daily Advance
                    $account['advance_for'] = 21; //Driver Advance in Daily Advance
                    $account['total'] = $request->amount;
                    

                    $transid = Transaction::create($account)->id;
                    $trash = ['type'=>24,'from'=>25,'id'=>$transid];
                    $transaction_id = Helper::transaction_id($trash);
                    Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);
                    
                    $income['transaction_id'] = $transid;
                    $income['payment_method'] = $request->method[$id];
                    $income['date'] = date("Y-m-d H:i:s");
                    $income['amount'] = $request->amount;
                    $income['remaining'] = 0;
                    $income['remarks'] = $request->remarks[$id];

                    IncomeExpense::create($income);
                }
            }
        }
        return redirect()->route('daily-advance.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(DailyAdvance $dailyAdvance)
    {
        // dd($dailyAdvance);
        $index['dailyAdvance'] = $dailyAdvance;
        $index['drivers'] = User::whereUser_type('D')->pluck('name','id');
        return view('daily_advance.edit',$index);
    }

    public function update(Request $request, DailyAdvance $dailyAdvance)
    {
        $formData = $request->all();
        $id = $request->id;
        $amount = $request->amount;
        // dd($formData);
        unset($formData['_token']);
        unset($formData['id']);
        unset($formData['_method']);
        $updated = DailyAdvance::where('id',$dailyAdvance->id)->update($formData);
        // dd($updated);
        $trns = Transaction::where(['from_id'=>$id,'param_id'=>25]);
        $trns->update(['total'=>$amount]);
        $trns_id = $trns->first()->id;
        // dd($trns_id);
        IncomeExpense::where('transaction_id',$trns_id)->update(['amount'=>$amount]);
        return redirect()->back();
        // dd($formData);

    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $trns = Transaction::where(['from_id'=>$id,'param_id'=>25]);
        $trns_id = $trns->first()->id;
        $trns->delete();
        // dd($trns_id);
        IncomeExpense::where('transaction_id',$trns_id)->delete();
        DailyAdvance::find($request->id)->delete();
        return redirect()->route('daily-advance.index');
    }

    public function view_event(Request $request){
        $index['advance']  = DailyAdvance::find($request->id);
        $index['historys'] = DailyAdvance::where('driver_id',$index['advance']->driver_id)->orderBy('date','DESC')->get();
        // dd($index);
        return view('daily_advance.view_event',$index);
    }

    public function get_remarks($ids){
        // dd($ids);
        $index['drivers'] = $drivers = explode(",", $ids);
        $index['users'] =  User::where('user_type','D')->whereIn('id',$drivers)->get();
        $index['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        // dd($index);
        return view('daily_advance.remarks',$index);
        // return view('daily-advance.view_event',)
    }

    public function report(){
        $yearArr = array();
        $dyear = DailyAdvance::whereNotNull('date')->distinct()->get([DB::raw('YEAR(date) as date')]);
        foreach($dyear as $year){
            // dd($year->date);
            $yearArr[$year->date]=$year->date;
        }
        $index['drivers'] = User::whereUserType('D')->pluck('name','id');

		$index['years'] = count($yearArr)>0 ? $yearArr : [date("Y")=>date("Y")];
		$index['month'] = Helper::getMonths();
		$index['request'] = null;
		$index['year_select'] = null;
		$index['month_select'] = null;
        // dd($index);
        return view('daily_advance.report',$index);
    }

    public function report_post(Request $request){
        // dd($request->all());
        $dyear = $request->year;
        $dmonth = !empty($request->month) ? ($request->month<10 ? "0".$request->month : $request->month) : null;
        $date = $dyear."-".$dmonth;
        $driver = $request->driver;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if(empty($from_date)){
            $exists = DailyAdvance::orderBy('date','ASC')->take(1)->exists();
            if($exists)
                $from_date = DailyAdvance::orderBy('date','ASC')->take(1)->first('date')->date;
            else
                $from_date=null;
        }
        if(empty($to_date)){
            $exists = DailyAdvance::orderBy('date','DESC')->take(1)->exists();
            if($exists)
                $to_date = DailyAdvance::orderBy('date','DESC')->take(1)->first('date')->date;
            else
                $to_date=null;
        }
        // $abc = [$dyear,$dmonth,$date,$driver,$from_date,$to_date];
        // dd($abc);
        

        if(empty($dmonth) && empty($driver))
			$dailyAdvance = DailyAdvance::select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
		elseif(empty($dmonth))
			$dailyAdvance = DailyAdvance::where('driver_id',$driver)->select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
		elseif(empty($driver)){
			$dailyAdvance = DailyAdvance::where('date','LIKE',"%$date%")->select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
            // dd($dailyAdvance->get());
        }
		else{
            // dd(1212);
			$dailyAdvance = DailyAdvance::where('driver_id',$driver)->where('date','LIKE',"%$date%")->select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
        }
        // dd($dailyAdvance->get());
        if(!empty($from_date) && empty(!$to_date)){
            $dailyAdvance = $dailyAdvance->whereBetween('date',[$from_date,$to_date])->get();
        }

        $advanceYear = DailyAdvance::whereNotNull('date')->distinct()->get([DB::raw('YEAR(date) as date')]);
        foreach($advanceYear as $year){
            // dd($year->date);
            $yearArr[$year->date]=$year->date;
        }
        $index['years'] = !empty($yearArr) ? $yearArr : [date("Y")=>date("Y")];
        $index['advances'] = $dailyAdvance;
        $index['drivers'] = User::whereUserType('D')->pluck('name','id');
        $index['request'] = $request->all();
        
        $index['driver_id'] = $driver;
        $index['result'] = "";
        // dd($index);
        return view('daily_advance.report',$index);
    }
    public function print_advance_driver(Request $request){
        $dyear = $request->year;
        $dmonth = !empty($request->month) ? ($request->month<10 ? "0".$request->month : $request->month) : null;
        $date = $dyear."-".$dmonth;
        $driver = $request->driver;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if(empty($from_date)){
            $exists = DailyAdvance::orderBy('date','ASC')->take(1)->exists();
            if($exists)
                $from_date = DailyAdvance::orderBy('date','ASC')->take(1)->first('date')->date;
            else
                $from_date=null;
        }
        if(empty($to_date)){
            $exists = DailyAdvance::orderBy('date','DESC')->take(1)->exists();
            if($exists)
                $to_date = DailyAdvance::orderBy('date','DESC')->take(1)->first('date')->date;
            else
                $to_date=null;
        }
        

        if(empty($dmonth) && empty($driver))
			$dailyAdvance = DailyAdvance::select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
		elseif(empty($dmonth))
			$dailyAdvance = DailyAdvance::where('driver_id',$driver)->select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
		elseif(empty($driver)){
			$dailyAdvance = DailyAdvance::where('date','LIKE',"%$date%")->select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
            // dd($dailyAdvance->get());
        }
		else{
            // dd(1212);
			$dailyAdvance = DailyAdvance::where('driver_id',$driver)->where('date','LIKE',"%$date%")->select('driver_id',DB::raw('SUM(amount) as amount'))->groupBy('driver_id');
        }
        // dd($dailyAdvance->get());
        if(!empty($from_date) && empty(!$to_date)){
            $dailyAdvance = $dailyAdvance->whereBetween('date',[$from_date,$to_date])->get();
        }
        $index['advances'] = $dailyAdvance;
        $index['date'] = collect(['from_date'=>$from_date,'to_date'=>$to_date]);
        return view('daily_advance.report-print',$index);
    }
    public function isPayrollChecked(Request $request){
        // dd($request);
        $date = date("Y-m",strtotime($request->date));
        foreach($request->drivers as $driver){
            $paycheck = DailyAdvance::where('date','LIKE',"%$date%")->where(['driver_id'=>$driver,'payroll_check'=>'1'])->exists() ? true : false;
            $driverName = User::find($driver)->name;
            $month = date("F, Y",strtotime($date));
            $msg = "Driver $driverName has aleady gotten the salary for the month of $month . Choose other drivers except $driverName ";
            // $index[$driver]['data-d'] = $date."-".$driver; 
            $index[$driver]['paycheck'] = $paycheck; 
            $index[$driver]['message'] = $paycheck ?  $msg: null; 

        }
        return response()->json($index);
    }
}