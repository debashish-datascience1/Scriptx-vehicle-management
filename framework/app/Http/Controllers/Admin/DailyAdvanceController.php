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
    // public function index()
    // {
    //     //Updating previous advance to driver details to transaction
    //     // $advances = AdvanceDriver::where('param_id', 7)->whereRaw("value IS NOT NULL")->get();
    //     // // dd($advances);
    //     // foreach ($advances as $advance) {
    //     //     $driver_id = Bookings::find($advance->booking_id)->driver_id;
    //     //     $pick_date = date("Y-m-d", strtotime(Bookings::find($advance->booking_id)->pickup));
    //     //     $array = ['driver_id' => $driver_id, 'date' => $pick_date, 'amount' => $advance->value, 'remarks' => $advance->remarks];
    //     //     // dd($array);
    //     //     $dailyid = DailyAdvance::create($array)->id;

    //     //     // dd($array);

    //     //     // Accounting
    //     //     if (!empty($advance->value)) {
    //     //         $account['from_id'] = $dailyid; //daily advance id
    //     //         $account['type'] = 24; //Debit 
    //     //         $account['bank_id'] = 1; //SELF CASH Bank Account
    //     //         $account['param_id'] = 25; //From Daily Advance
    //     //         $account['advance_for'] = 21; //Driver Advance in Daily Advance
    //     //         $account['total'] = bcdiv($advance->value, 1, 2);


    //     //         $transid = Transaction::create($account)->id;
    //     //         $trash = ['type' => 24, 'from' => 25, 'id' => $transid];
    //     //         $transaction_id = Helper::transaction_id($trash);
    //     //         Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

    //     //         $income['transaction_id'] = $transid;
    //     //         $income['payment_method'] = 17;
    //     //         $income['date'] = $pick_date;
    //     //         $income['amount'] = bcdiv($advance->value, 1, 2);
    //     //         $income['remaining'] = 0;
    //     //         $income['remarks'] = $advance->remarks;

    //     //         IncomeExpense::create($income);
    //     //     }
    //     // }


    //     $index['dailys'] = DailyAdvance::orderBy('id', 'DESC')->get();
    //     foreach ($index['dailys'] as $d) {
    //         $trash = Transaction::where(['from_id' => $d->id, 'param_id' => 25]);
    //         $d->is_transaction = $trash->exists() ? true : false;
    //     }
    //     // dd($index);
    //     return view('daily_advance.index', $index);
    // }
    public function index()
    {
        $dailys = DailyAdvance::orderBy('id', 'DESC')->paginate(50); // Adjust the number as needed

        foreach ($dailys as $d) {
            $d->is_transaction = Transaction::where(['from_id' => $d->id, 'param_id' => 25])->exists();
        }

        return view('daily_advance.index', compact('dailys'));
    }

    public function create()
    {
        $index['driver'] = User::whereUser_type('D')->pluck('name', 'id');
        $index['purse'] = $this->dough();
        // dd($index);
        return view('daily_advance.create', $index);
    }

    public function dough()
    {
        $startingAmount = BankAccount::find(1)->starting_amount;
        $deposits = $index['deposits'] = BankTransaction::where('bank_id', 1)->sum('amount');
        $total_dough = (float) $startingAmount +  (float) $deposits;
        $spent = $index['spent'] = Transaction::where(['bank_id' => 1, 'type' => 24])->sum('total');
        $rem = $total_dough - $spent;
        return $rem;
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        $driver_ids = $request->driver_id;
        $date = !empty($request->date) ? date("Y-m-d", strtotime($request->date)) : null;
        if (count($driver_ids) > 0) {
            foreach ($driver_ids as $id) {
                $array = ['driver_id' => $id, 'date' => $date, 'amount' => $request->amount, 'remarks' => $request->remarks[$id]];
                // dd($array);
                $dailyid = DailyAdvance::create($array)->id;

                

                // Accounting
                if (!empty($request->amount)) {
                    $account['from_id'] = $dailyid; //daily advance id
                    $account['type'] = 24; //Debit 
                    $account['bank_id'] = 1; //SELF CASH Bank Account
                    $account['param_id'] = 25; //From Daily Advance
                    $account['advance_for'] = 21; //Driver Advance in Daily Advance
                    $account['total'] = $request->amount;

                    $transid = Transaction::create($account)->id;

                    $trash = ['type' => 24, 'from' => 25, 'id' => $transid];
                    $transaction_id = Helper::transaction_id($trash);
                    Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                    $income['transaction_id'] = $transid;
                    $income['payment_method'] = $request->method[$id];
                    $income['date'] = $date;
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
        $index['drivers'] = User::whereUser_type('D')->pluck('name', 'id');
        return view('daily_advance.edit', $index);
    }

    public function update(Request $request, DailyAdvance $dailyAdvance)
    {
        $formData = $request->all();
        $id = $request->id;
        $amount = $request->amount;
        $date = !empty($request->date) ? date("Y-m-d", strtotime($request->date)) : null;
        // dd($formData);
        unset($formData['_token']);
        unset($formData['id']);
        unset($formData['_method']);
        $formData['date'] = $date;
        $updated = DailyAdvance::where('id', $dailyAdvance->id)->update($formData);
        // dd($updated);
        $trns = Transaction::where(['from_id' => $id, 'param_id' => 25]);
        $trns->update(['total' => $amount]);
        $trns_id = $trns->first()->id;
        // dd($trns_id);
        IncomeExpense::where('transaction_id', $trns_id)->update(['amount' => $amount]);
        return redirect()->back();
        // dd($formData);

    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $trns = Transaction::where(['from_id' => $id, 'param_id' => 25]);
        $trns_id = $trns->first()->id;
        $trns->delete();
        // dd($trns_id);
        IncomeExpense::where('transaction_id', $trns_id)->delete();
        DailyAdvance::find($request->id)->delete();
        return redirect()->route('daily-advance.index');
    }

    public function view_event(Request $request)
    {
        $index['advance']  = DailyAdvance::find($request->id);
        $index['historys'] = DailyAdvance::where('driver_id', $index['advance']->driver_id)->orderBy('date', 'DESC')->get();
        // dd($index);
        return view('daily_advance.view_event', $index);
    }

    public function get_remarks($ids)
    {
        // dd($ids);
        $index['drivers'] = $drivers = explode(",", $ids);
        $index['users'] =  User::where('user_type', 'D')->whereIn('id', $drivers)->get();
        $index['methods'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
        // dd($index);
        return view('daily_advance.remarks', $index);
        // return view('daily-advance.view_event',)
    }
    public function isPayrollChecked(Request $request)
    {
        // dd($request);
        $date = date("Y-m", strtotime($request->date));
        foreach ($request->drivers as $driver) {
            $paycheck = DailyAdvance::where('date', 'LIKE', "%$date%")->where(['driver_id' => $driver, 'payroll_check' => '1'])->exists() ? true : false;
            $driverName = User::find($driver)->name;
            $month = date("F, Y", strtotime($date));
            $msg = "Driver $driverName has aleady gotten the salary for the month of $month . Choose other drivers except $driverName ";
            // $index[$driver]['data-d'] = $date."-".$driver; 
            $index[$driver]['paycheck'] = $paycheck;
            $index[$driver]['message'] = $paycheck ?  $msg : null;
        }
        return response()->json($index);
    }
}
