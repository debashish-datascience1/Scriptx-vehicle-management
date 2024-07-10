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
    public function manage_payroll()
    {
        $index['payrolls'] = Payroll::orderBy('id', 'desc')->get();
        // dd($index);
        return view('payroll.manage', $index);
    }
    public function index()
    {
        // $index['data'] = Payroll::orderBy('id','desc')->get();
        $index['data'] = User::where('user_type', 'D')->orderBy('name', 'asc')->get();
        // dd($index['data']);
        // $payroll = Payroll::find(1);
        return view('payroll.index', $index);
    }

    public function create()
    {
        $index['data'] = User::whereUser_type("D")->orderBy('id', 'desc')->pluck('name', 'id');
        $index['months'] = [
            '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
        $yearArr = array();
        $year = date('Y');
        for ($i = $year - 2; $i <= $year + 2; $i++) {
            $yearArr[$i] = $i;
        }
        $index['years'] = $yearArr;
        // dd($index);
        return view('payroll.create', $index);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'drivers' => 'required',
            'driver_id' => 'required',
            'salary' => 'required',
            'month' => 'required',
            'year' => 'required',
            'working_days' => 'required',
            'absent_days' => 'required',
            'advance_salary' => 'required',
            'advance_driver' => 'required',
            'total_payable_salary' => 'required',
            'payable_salary' => 'required',
            'carried_salary' => 'required',
            'payroll_remarks' => 'required',
        ]);
        $driver_id = $request->driver_id;
        $salary = $request->salary;
        $month = $request->month;
        $year = $request->year;
        $working_days = $request->working_days;
        $absent_days = $request->absent_days;
        $deduct_salary = $request->deduct_salary;
        $advance_salary = $request->advance_salary;
        $advance_driver = $request->advance_driver;
        $total_payable_salary = $request->total_payable_salary;
        $payable_salary = $request->payable_salary;
        $carried_salary = $request->carried_salary;
        $payroll_remarks = $request->payroll_remarks;

        $month_name = Helper::getMonth($month, true);
        $driver_name = ucwords(User::find($driver_id)->name);

        if (Payroll::where(['user_id' => $driver_id, 'for_month' => $month, 'for_year' => $year])->exists())
            return back()->withErrors("$driver_name's salary for month of $month_name, $year has already been paid.");
        else {
            $for_date = $year . "-" . $month . "-01";
            $remaining_salary = $total_payable_salary - $payable_salary;

            //update prev remaining salary status to 1->complete
            Payroll::where('user_id', $driver_id)->whereRaw('is_complete IS NULL')->update(['is_complete' => 1]);


            $id = Payroll::create([
                'user_id' => $driver_id,
                'salary' => $salary,
                'date' => date("Y-m-d"),
                'for_date' => $for_date,
                'payable_salary' => $payable_salary,
                'remaining_salary' => $remaining_salary, //amount yet to pay to driver
                'for_month' => $month,
                'for_year' => $year,
            ])->id;



            $formated_month = $month < 10 ? "0" . $month : $month;
            $date = $year . "-" . $formated_month;
            $payroll = Payroll::find($id);

            //Booking ids
            $bookids = Bookings::where('driver_id', $driver_id)->where(function ($query) {
                $query->where('bookings.payroll_check', 0)
                    ->orWhereRaw('bookings.payroll_check IS NULL');
            })->where('pickup', 'LIKE', "%$date%")->pluck('id')->toArray();
            // dd($bookids);
            $payroll->setMeta([
                'working_days' => $working_days,
                'absent_days' => $absent_days,
                'carried_salary' => $carried_salary,
                'advance_salary' => $advance_salary,
                'advance_driver' => $advance_driver,
                'deduct_salary' => $deduct_salary,
                'total_payable_salary' => $total_payable_salary,
                'payable_salary' => $payable_salary,
                'payroll_remarks' => $payroll_remarks,
                'bookids' => base64_encode(serialize($bookids)),
            ]);
            $payroll->save();

            // Accounting
            if (!empty($payable_salary)) {

                $account['from_id'] = $id; //Payroll Id
                $account['type'] = 24; //Credit 
                $account['bank_id'] = 1; //Bank SELF 
                $account['param_id'] = 19; //From Payroll
                $account['advance_for'] = null; //Driver Advance in Payroll
                $account['total'] = $payable_salary;


                $transid = Transaction::create($account)->id;
                $trash = ['type' => 24, 'from' => 19, 'id' => $transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                $income['transaction_id'] = $transid;
                $income['payment_method'] = 16;
                $income['date'] = date("Y-m-d H:i:s");
                $income['amount'] = $payable_salary;
                $income['remaining'] = 0;
                $income['remarks'] = $payroll_remarks;

                IncomeExpense::create($income);

                if (!empty($bookids)) {
                    foreach ($bookids as $id) {
                        $updatebook = Bookings::find($id);
                        $updatebook->payroll_check = 1;
                        $updatebook->save();
                    }
                }
                DailyAdvance::where('date', 'LIKE', "%$date%")->where('driver_id', $driver_id)->where('payroll_check', null)->update(['payroll_check' => '1']);
            }
        }
        return redirect()->route('payroll.index');
    }

    public function show(Payroll $payroll)
    { }

    public function edit(Payroll $payroll)
    { }

    public function update(Request $request, Payroll $payroll)
    { }

    public function destroy(Payroll $payroll)
    { }



    public function single_pay(Request $request)
    {
        // dd($request->id);
        $index['user'] = User::find($request->id);

        // Cash
        $startingAmount = BankAccount::find(1)->starting_amount;
        $deposits = $index['deposits'] = BankTransaction::where('bank_id', 1)->sum('amount');
        $total_dough = (float) $startingAmount +  (float) $deposits;
        $spent = $index['spent'] = Transaction::where(['bank_id' => 1, 'type' => 24])->sum('total');
        $index['remaining'] = $total_dough - $spent;

        // dd($index['user']->getMeta('salary'));
        // $index['user']->
        // dd($index);
        return view('payroll.pay', $index);
    }

    public function getWorkingDays(Request $request)
    {
        // dd($request->all());
        $did = $request->driver_id;
        $rmonth = $request->month;
        $ryear = $request->year;
        $working_days = $request->working_days;
        $month = $rmonth < 10 ? '0' . $rmonth : $rmonth;
        $date = $ryear . "-" . $month;
        // dd($request->driver_id);
        $leave = Leave::where('driver_id', $did)
            ->where('date', 'LIKE', "%$date%")
            ->where('is_present', 1)->get();
        $halfLeave = Leave::where('driver_id', $did)
            ->where('date', 'LIKE', "%$date%")
            ->whereIn('is_present', [3, 4])->get();
        $index['leave'] = $leave;
        $index['halfLeave'] = $halfLeave;
        $presentDays = $leave->count() + ($halfLeave->count() * .5);
        if (!empty($working_days)) $presentDays = $working_days;

        $totalMonthDays = date('t', strtotime($date));
        $absentDays = $totalMonthDays - $presentDays;

        //Calculating advances
        //salary advance also includes "Advance to driver" in Mark as complete function in Booking
        $salary_advance = DailyAdvance::where('date', 'LIKE', "%$date%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->sum('amount');
        // dd(Bookings::where('driver_id',$did)->get());
        $bookingData = Bookings::where('driver_id', $did)->where(function ($query) {
            $query->where('bookings.payroll_check', 0)
                ->orWhereRaw('bookings.payroll_check IS NULL');
        })->where('pickup', 'LIKE', "%$date%");
        $booking_ids = $bookingData->pluck('id')->toArray();
        // dd($booking_ids);
        if (!empty($booking_ids)) {
            // dd($did,$booking_ids);
            $bookingAdvance = AdvanceDriver::whereIn('booking_id', $booking_ids)->where('param_id', 7)->sum('value');
            // dd($bookingAdvance);
        } else $bookingAdvance = 0;

        //check if all the booking ids are marked as complete
        $yetToComplete = $bookingData->where('status', '!=', 1)->exists() ? $bookingData->where('status', '!=', 1)->get() : null;
        $advanceFromBooking = Bookings::where('driver_id', $did)->where('pickup', 'LIKE', "%$date%")->meta()->where(function ($query) {
            $query->where('bookings_meta.key', 'advance_pay')
                ->whereRaw('bookings_meta.value IS NOT NULL');
        })->get();

        $userData =  User::where('id', $did)->first();
        $gross_salary = $userData->salary;
        $user_vehicle =  !empty($userData->driver_vehicle->vehicle) ? $userData->driver_vehicle->vehicle->license_plate : "-";
        $payable_salary = $gross_salary - ($salary_advance + $bookingAdvance);
        // $payable_salary = $gross_salary-($salary_advance);

        if ($totalMonthDays == $absentDays && $presentDays == 0) {
            $payable_salary = 0;
            $deduct_amount = 0;
        } else {
            $perday = bcdiv($gross_salary / $totalMonthDays, 1, 2);
            $deduct_amount = bcdiv($absentDays * $perday, 1, 2);
            $payable_salary = $payable_salary - $deduct_amount;
        }

        // $index['abccccc'] = Bookings::where('driver_id',$did)->where('pickup','LIKE',"%$date%")->meta()->where(function($query){
        //     $query->where('bookings_meta.key','advance_pay')
        //           ->whereRaw('bookings_meta.value IS NOT NULL');
        // })->get();
        //Check leave
        $isLeaveChecked = Leave::where('driver_id', $did)->where('date', 'LIKE', "%$date%")->exists() ?  true : false;
        $carried_salary = Payroll::where('user_id', $did)->whereRaw("is_complete IS NULL")->sum('remaining_salary');

        // $adDriver = $advDriver;

        $index['gross_salary'] = $gross_salary;
        $index['payable_salary'] = bcdiv($payable_salary, 1, 2);
        $index['salary_advance'] = bcdiv($salary_advance, 1, 2);
        $index['salary_details'] =  DailyAdvance::where('date', 'LIKE', "%$date%")->where(['driver_id' => $did, 'payroll_check' => null, 'advance_driver_id' => null])->get();
        $index['bookingAdvance'] = $bookingAdvance;
        $index['presentDays'] = $presentDays;
        $index['absentDays'] = $absentDays;
        $index['yetToComplete'] = $yetToComplete;
        $index['deduct_amount'] = $deduct_amount;
        $index['isLeaveChecked'] = $isLeaveChecked;
        $index['totalMonthDays'] = $totalMonthDays;
        $index['userData'] = $userData;
        $index['bookingData'] = $bookingData->get();
        $index['isAlreayPaid'] = Payroll::where('user_id', $did)->where('for_date', 'LIKE', "%$date%")->exists() ? true : false;
        $index['carried_salary'] = $carried_salary;
        $index['total_payable_salary'] = bcdiv($payable_salary + $carried_salary, 1, 2);
        $index['advanceFromBooking'] = $advanceFromBooking;
        $index['imonth'] = date("F-Y", strtotime($date . "-01"));
        // $index['asdsa'] = count($advanceFromBooking)>0 ? $advanceFromBooking->first()->advanceToDriver : null;

        $index['view'] = view('payroll.expenses', $index)->render();
        // dd($advanceFromBooking->first()->advanceFromBooking);
        return response()->json($index);
    }

    public function view_event(Request $request)
    {
        // dd($request->id);
        $index['payroll'] = Payroll::find($request->id);
        // dd(base64_decode($index['payroll']->bookids));
        // dd(unserialize(base64_decode($index['payroll']->bookids)));
        if (!empty($index['payroll']->bookids)) {
            $bookids = unserialize(base64_decode($index['payroll']->bookids));
            if (!empty($bookids)) {
                foreach ($bookids as $id) {
                    $index['advanced'][] = AdvanceDriver::where('booking_id', $id)->get();
                }
            } else {
                $index['advanced'][] = null;
            }
            // dd($bookids);
        } else {
            $index['advanced'][] = null;
        }

        $index['advheads'] = Params::where('code', 'AdvanceDriver')->select('id', 'label')->get();
        $paramids = Params::where('code', 'AdvanceDriver')->get('id');
        foreach ($paramids as $ids) {
            $index['ids'][] = $ids->id;
        }
        // dd($index);
        return view('payroll.view_event', $index);
    }

    public function purse(Request $request)
    {
        // return $request->all();
        $index['can'] = $request->remain >= $request->paysal ? true : false;
        return response()->json($index);
    }

    public function payabletype(Request $request)
    {
        $index['ismore'] = $request->total >= $request->payable ? false : true;
        return response()->json($index);
    }
}
