<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Payroll;
use App\Model\Bookings;
use App\Model\Leave;
use App\Model\AdvanceDriver;
use App\Model\DailyAdvance;
use App\Model\Params;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\BankTransaction;
use App\Model\BankAccount;

use Helper;

class PayrollController extends Controller
{
    public function manage_payroll(){
        $index['payrolls'] = Payroll::orderBy('id','desc')->get();
        // dd($index);
        return view('payroll.manage',$index);
    }
    public function index()
    {
        // $index['data'] = Payroll::orderBy('id','desc')->get();
        $index['data'] = User::where('user_type','D')->orderBy('id','desc')->get();
        // dd($index['data']);
        // $payroll = Payroll::find(1);
        return view('payroll.index',$index);
    }

    public function create(){
        $index['data'] = User::whereUser_type("D")->orderBy('id', 'desc')->pluck('name','id');
        $index['months'] = ['1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December'
        ];
        $yearArr = array();
        $year = date('Y');
        for($i=$year-2;$i<=$year+2;$i++){
            $yearArr[$i]=$i; 
        }
        $index['years'] = $yearArr;
        // dd($index);
        return view('payroll.create',$index);
    }
    
    public function store(Request $request){
        
        // dd($request->all());
        $request->validate([
            'drivers'=>'required',
            'driver_id'=>'required',
            'salary'=>'required',
            'month'=>'required',
            'year'=>'required',
        ]);
        $month_name = Helper::getMonth($request->month,true);
        $driver_name = ucwords(User::find($request->driver_id)->name);

        if(Payroll::where(['user_id'=>$request->driver_id,'for_month'=>$request->month,'for_year'=>$request->year])->exists())
            return back()->withErrors("$driver_name's salary for month of $month_name, $request->for_year has already been paid.");
        else
        {
            $for_date = $request->year."-".$request->month."-01";
            $id = Payroll::create([
                'user_id'=>$request->driver_id,
                'salary'=>$request->salary,
                'date'=>date("Y-m-d"),
                'date'=>$for_date,
                'payable_salary'=>$request->payable_salary,
                'for_month'=>$request->month,
                'for_year'=>$request->year,
            ])->id;
            $month = $request->month<10 ? "0".$request->month : $request->month;
            $date = $request->year."-".$month;
            $payroll = Payroll::find($id);   
            $payroll->setMeta([
                'working_days'=> $request->working_days,
                'advance_salary'=> $request->advance_salary,
                'payroll_remarks'=> $request->payroll_remarks,
                'bookids'=> $request->bookids,
            ]);
            $payroll->save();
            if(!empty($request->bookids)){
                foreach(unserialize(base64_decode($request->bookids)) as $id){
                    $updatebook = Bookings::find($id);
                    $updatebook->payroll_check = 1;
                    $updatebook->save();
                }
            }
            DailyAdvance::where('date','LIKE',"%$date%")->where('driver_id',$request->driver_id)->where('payroll_check',null)->update(['payroll_check'=>'1']);

            // Accounting
            if(!empty($request->payable_salary)){
                $account['from_id'] = $id; //Payroll Id
                $account['type'] = 24; //Credit 
                $account['bank_id'] = 1; //Bank SELF 
                $account['param_id'] = 19; //From Payroll
                $account['advance_for'] = null; //Driver Advance in Payroll
                $account['total'] = $request->payable_salary;
                

                $transid = Transaction::create($account)->id;
                $trash = ['type'=>24,'from'=>19,'id'=>$transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);
                
                $income['transaction_id'] = $transid;
                $income['payment_method'] = 16;
                $income['date'] = date("Y-m-d H:i:s");
                $income['amount'] = $request->payable_salary;
                $income['remaining'] = 0;
                $income['remarks'] = $request->payroll_remarks;

                IncomeExpense::create($income);
            }

        }
        return redirect()->route('payroll.index');

    }

    public function show(Payroll $payroll){

    }

    public function edit(Payroll $payroll){

    }

    public function update(Request $request,Payroll $payroll){

    }

    public function destroy(Payroll $payroll){

    }

    

    public function single_pay(Request $request){
        // dd($request->id);
        $index['user'] = User::find($request->id);

        // Cash
        $startingAmount = BankAccount::find(1)->starting_amount;
        $deposits = $index['deposits'] = BankTransaction::where('bank_id',1)->sum('amount');
        $total_dough = (float) $startingAmount +  (float) $deposits;
        $spent = $index['spent'] = Transaction::where(['bank_id'=>1,'type'=>24])->sum('total');
        $index['remaining'] = $total_dough - $spent;

        // dd($index['user']->getMeta('salary'));
        // $index['user']->
        // dd($index);
        return view('payroll.pay',$index);
    }

    public function getWorkingDays(Request $request){
        // dd($request->all());
        $rmonth = $request->month;
        $ryear = $request->year;
        $month = $rmonth<10 ? '0'.$rmonth : $rmonth;
        $date = $ryear."-".$month;
        // dd($request->driver_id);
        $leave = Leave::where('driver_id',$request->driver_id)
                        ->where('date','LIKE',"%$date%")
                        ->where('is_present',1)->get();
        $halfLeave = Leave::where('driver_id',$request->driver_id)
                        ->where('date','LIKE',"%$date%")
                        ->whereIn('is_present',[3,4])->get();
        $index['leave'] = $leave;
        $index['halfLeave'] = $halfLeave;
        $presentDays = $leave->count() + ($halfLeave->count()*.5);
        // $index['booking'] = Bookings::where('pickup','LIKE',"%$date%")->where('driver_id',$request->driver_id)->get();
        $driver = User::find($request->driver_id);
        // $index['booking'] = $bookings = $driver->driver_booking->where('payroll_check','!=',1);
        $index['booking'] = $bookings = Bookings::where(['driver_id'=>$driver->id,'payroll_check'=>!1])->where('pickup','LIKE',"%$date%")->get();
        // dd()
        //From Advance Driver
        if($bookings->count()){
            foreach($bookings as $booking){
                // $booking->id;
                $advanceDriver = AdvanceDriver::where(['booking_id'=>$booking->id,'param_id'=>'7'])->select('value');
                $bookingids[]=$booking->id;
                if(AdvanceDriver::where(['booking_id'=>$booking->id])->exists())
                {
                    $advDriver[]= AdvanceDriver::where(['booking_id'=>$booking->id])->get() ;
                    
                }
                else
                    $advDriver=[];
                $advance[] = $advanceDriver->exists() ? $advanceDriver->first()->value : 0 ;
                // dd($advanceDriver);
            }
        }else{
            $advance=[];
            $advDriver=[];
            $bookingids=[];
        }

        // Daily Advance
        $dailyAdvance_sum = DailyAdvance::where('date','LIKE',"%$date%")->where('driver_id',$request->driver_id)->where('payroll_check',null)->sum('amount');

        // $adDriver = $advDriver;
        $index['advsalary'] = array_sum($advance)+$dailyAdvance_sum;
        $fullSal = User::find($request->driver_id)->getMeta('salary');
        $remaining = $fullSal - $index['advsalary'];
        $index['salary'] = $remaining;
        $index['dailyAdvance_sum'] = $dailyAdvance_sum;
        $index['presentDays'] = $presentDays;
        $index['advances'] = collect($advDriver);
        $imonth = date('F',strtotime($date."-01"));
        
        // return response()->json(array_sum($advance));
        $help = Helper::arrayFlatten($advDriver,true);
        $index['bookingids'] = $bookids = $bookingids;
        $index['help'] = $help;
        $index['advance'] = $advance;
        $index['view'] = view('payroll.expenses',compact('help','imonth','bookids'))->render();
        // dd($index);
        return response()->json($index);
    }

    public function view_event(Request $request){
        // dd($request->id);
        $index['payroll'] = Payroll::find($request->id);
        if(!empty($index['payroll']->bookids)){
            $bookids = unserialize(base64_decode($index['payroll']->bookids));
            foreach($bookids as $id){
                $index['advanced'][] = AdvanceDriver::where('booking_id',$id)->get();
            }
        }else{
            $index['advanced'][] = null;
        }
        
        $index['advheads'] = Params::where('code','AdvanceDriver')->select('id','label')->get();
        $paramids = Params::where('code','AdvanceDriver')->get('id');
        foreach($paramids as $ids){
            $index['ids'][] = $ids->id;
        }
        // dd($index);
        return view('payroll.view_event',$index);
    }

    public function purse(Request $request){
        // return $request->all();
        $index['can'] = $request->remain >= $request->paysal ? true :false;
        return response()->json($index);
    }
}

?>