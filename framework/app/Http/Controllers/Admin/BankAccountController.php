<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use DB;
use App\Model\BankAccount;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\User;
use App\Model\Vendor;
use App\Model\Bookings;
use App\Model\FuelModel;
use App\Model\PartsModel;
use App\Model\BulkPayment;
use App\Model\WorkOrders;
use App\Model\BankTransaction;
use App\Model\TransactionHistory;
use App\Model\DailyAdvance;
use App\Model\BulkList;
use App\Model\Params;
use App\Model\PartsInvoice;
use App\Model\ShortageModel;
use Illuminate\Http\Request;
use Helper;
use Illuminate\Support\Carbon;

class BankAccountController extends Controller
{
    public function index()
    {
        // dd("index");
        $index['bankAccount'] = BankAccount::get();
        $index['is_self'] = [1 => 'Yes', 2 => 'No'];
        $index['banks'] = BankAccount::where('id', '!=', 1)->pluck('bank', 'id');
        foreach ($index['bankAccount'] as $b) {
            $total = BankTransaction::where('bank_id', $b->bank_id)->sum('amount');
            $paid = Transaction::where('bank_id', $b->bank_id)->sum('total');
            $allTotal = $b->starting_amount + $total;
            $b->total = $allTotal;
            $b->remain = $allTotal - $paid;
        }
        // dd($index);
        return view('bank_account.index', $index);
    }

    public function create()
    {
        return view('bank_account.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $formData = $request->all();
        unset($formData['_token']);
        $id = BankAccount::create($formData)->id;
        // dd(BankAccount::find($id));
        $account['from_id'] = $id; //Bank id
        $account['type'] = 23; //Credit 
        $account['param_id'] = 29; //From Starting Amount
        $account['bank_id'] = $id; //From Bank id
        $account['advance_for'] = null; //No advance given
        $account['total'] = BankAccount::find($id)->starting_amount;


        $transid = Transaction::create($account)->id;
        $trash = ['type' => 23, 'from' => 29, 'id' => $transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

        $income['transaction_id'] = $transid;
        $income['payment_method'] = 16; //Cash
        $income['date'] = date("Y-m-d H:i:s");
        $income['amount'] = BankAccount::find($id)->starting_amount;
        $income['remaining'] = 0;
        $income['remarks'] = null;

        IncomeExpense::create($income);
        return redirect()->route('bank-account.index');
    }

    public function edit(BankAccount $bankAccount)
    {
        // dd($bankAccount);
        $index['bankAccount'] = $bankAccount;
        return view('bank_account.edit', $index);
    }

    public function  show()
    {
        //
    }

    public function update(BankAccount $bankAccount, Request $request)
    {
        // dd($bankAccount->id);
        // dd($request->all());
        $formData = $request->all();
        unset($formData['_method']);
        unset($formData['_token']);

        // Transaction
        $transa = Transaction::where(['from_id' => $bankAccount->id, 'param_id' => 29]);
        $transaid = $transa->first()->id;
        $transa->update(['total' => $request->starting_amount]);

        // Income Expense
        IncomeExpense::where('transaction_id', $transaid)->update(['amount' => $request->starting_amount]);
        BankAccount::where('id', $bankAccount->id)->update($formData);
        return redirect()->route('bank-account.index');
    }

    public function destroy($id)
    {
        //
    }

    public function view_event($id)
    {
        // dd(BankAccount::find($id));
        $index['bankAccount'] = BankAccount::find($id);
        $index['history'] = BankTransaction::where('bank_id', $id)->orderBy('id', 'DESC')->take(10)->get();
        return view('bank_account.view_event', $index);
    }
    public function bulk_pay()
    {
        $index['customers'] = User::where('user_type', 'C')->pluck('name', 'id');
        $index['vend'] = Vendor::get();
        $index['request'] = null;
        foreach ($index['vend'] as $v) {
            $index['vendors'][$v->id] = $v->name . " ( $v->type )";
        }
        $index['vendor'] = null;
        $index['customer'] = null;
        $index['vendorSelect'] = null;
        $index['customerSelect'] = null;
        $index['from_date'] = "";
        $index['to_date'] = "";

        if (Helper::string_exists("bulk_pay", url()->current()))
            return view('bank_account.bulk_pay', $index);
        else
            return view('bank_account.bulk_receive', $index);
    }

    public function bulk_paypost(Request $request)
    {
        $customer = $request->customer;
        $vendor = $request->vendor;
        $start = $request->get('from_date') ? date("Y-m-d", strtotime($request->get('from_date'))) : null;
        $end = $request->get('to_date') ? date("Y-m-d", strtotime($request->get('to_date'))) : null;

        // Determine if this is bulk pay or bulk receive based on customer/vendor
        $isBulkPay = empty($customer) && !empty($vendor);

        // Initialize the base query
        $query = Transaction::query();
        
        if ($isBulkPay) {
            // For bulk pay - exclude booking transactions
            $query->whereIn('param_id', [20, 26, 28]); // Fuel, Parts, Work Order
        } else {
            // For bulk receive - only show booking transactions
            $query->where('param_id', 18); // Bookings only
        }

        // Add common query conditions
        $query->where(function ($query) {
                $query->whereNull('is_completed')
                    ->orWhere('is_completed', 2);
            })
            ->where(function ($q) {
                $q->where('advance_for', 22)
                    ->orWhereNull('advance_for');
            })
            ->whereNull('deleted_at');

        // Apply date range filter if dates are provided
        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($start) {
            $query->where('created_at', '>=', $start);
        } elseif ($end) {
            $query->where('created_at', '<=', $end);
        }

        $index['transactions'] = $query->get();

        // Process transactions and add required fields
        foreach ($index['transactions'] as $k => $t) {
            if ($t->param_id == 18) { //Booking
                $booking = $t->booking;
                if ($booking && (!$customer || $booking->customer_id == $customer)) {
                    $t->org_id = $booking->customer_id;
                    $t->date = $booking->created_at;
                    $t->dateof = $booking->pickup;
                    $t->vc = $booking->customer ? $booking->customer->name : null;
                } else {
                    continue;
                }
            } elseif ($t->param_id == 20) { //Fuel
                $fuel = $t->fuel;
                if ($fuel && (!$vendor || $fuel->vendor_name == $vendor)) {
                    $t->org_id = $fuel->vendor_name;
                    $t->date = $fuel->created_at;
                    $t->dateof = $fuel->date ? date("Y-m-d H:i:s", strtotime($fuel->date)) : null;
                    $t->vc = $fuel->vendor ? $fuel->vendor->name : null;
                } else {
                    continue;
                }
            } elseif ($t->param_id == 26) { //Parts
                $partsInvoice = $t->partsInvoice;
                if ($partsInvoice && (!$vendor || $partsInvoice->vendor_id == $vendor)) {
                    $t->org_id = $partsInvoice->vendor_id;
                    $t->date = $t->dateof = $partsInvoice->created_at;
                    $t->vc = $partsInvoice->vendor ? $partsInvoice->vendor->name : null;
                } else {
                    continue;
                }
            } elseif ($t->param_id == 28) { //Work Order
                $workOrder = $t->workorders;
                if ($workOrder && (!$vendor || $workOrder->vendor_id == $vendor)) {
                    $t->org_id = $workOrder->vendor_id;
                    $date_stuff = $workOrder->required_by ?? $workOrder->created_at;
                    $t->date = $t->dateof = $date_stuff ? date("Y-m-d H:i:s", strtotime($date_stuff)) : null;
                    $t->vc = $workOrder->vendor ? $workOrder->vendor->name : null;
                } else {
                    continue;
                }
            }

            $t->rem = $t->incExp ? $t->incExp->remaining : null;
        }

        // Filter valid transactions
        $filtered = $index['transactions']->filter(function ($t) {
            return $t->org_id !== null && $t->rem != 0 && $t->dateof !== null;
        });

        $index['transactions'] = $filtered;

        // Populate index array with required data
        $index['customers'] = User::where('user_type', 'C')->pluck('name', 'id');
        $index['vend'] = Vendor::get();
        foreach ($index['vend'] as $v) {
            $index['vendors'][$v->id] = $v->name . " ( $v->type )";
        }
        $index['vendor'] = $request->has('vendor') ? $request->vendor : null;
        $index['customer'] = $request->has('customer') ? $request->customer : null;
        
        if (empty($request->vendor) && empty($request->customer)) {
            $index['vendorSelect'] = $index['customerSelect'] = null;
        } else {
            $index['vendorSelect'] = $request->has('vendor') && !empty($request->vendor) ? null : 'disabled';
            $index['customerSelect'] = $request->has('customer') && !empty($request->customer) ? null : 'disabled';
        }
        
        $index['result'] = "";
        if (!empty($customer)) {
            $index['custvend'] = User::find($customer)->name;
        } elseif (!empty($vendor)) {
            $index['custvend'] = Vendor::find($vendor)->name;
        } else {
            $index['custvend'] = null;
        }

        $index['bankAccounts'] = BankAccount::select("id", DB::raw("CONCAT(bank,'(',account_no,')') as bank"))->pluck('bank', 'id');
        $index['totalAmount'] = $index['transactions']->sum('total');

        // Add vehicle information for each transaction
        foreach ($index['transactions'] as $tr) {
            $tr->remainingAmt = $tr->incExp ? $tr->incExp->remaining : null;
            $vehicleExistParams = [18, 20, 28]; //Booking,Fuel,Work Order
            if (in_array($tr->param_id, $vehicleExistParams)) {
                if ($tr->param_id == 18) { //Bookings
                    $vehicle = $tr->booking->vehicle;
                } else if ($tr->param_id == 20) { //Fuel
                    $vehicle = $tr->fuel->vehicle_data;
                } else if ($tr->param_id == 28) { //Work Orders
                    $vehicle = $tr->workorders->vehicle;
                }
                $tr->license_plate = $vehicle ? $vehicle->license_plate : null;
            } else {
                $tr->license_plate = null;
            }
        }

        $index['remm'] = $index['transactions']->sum('remainingAmt');
        if ($request->has('customer') && !empty($customer)) {
            $index['faulty'] = [1 => 'Remaining', 2 => 'Completed', 3 => 'Shortage', 4 => "Driver's Fault"];
        } else {
            $index['faulty'] = [1 => 'Remaining', 2 => 'Completed'];
        }

        $index['request'] = $request->all();
        $index['method'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
        $index['from_date'] = $request->get('from_date');
        $index['to_date'] = $request->get('to_date');
        $index['now'] = date("Y-m-d");

        // Return appropriate view based on whether it's bulk pay or receive
        if ($customer != "") {
            return view('bank_account.bulk_receive', $index);
        } else {
            return view('bank_account.bulk_pay', $index);
        }
    }

    private function processBooking($t, $customer)
    {
        $booking = $t->booking;
        if ($booking && (!$customer || $booking->customer_id == $customer)) {
            $t->org_id = $booking->customer_id;
            $t->date = $booking->created_at;
            $t->dateof = $booking->pickup;
            $t->vc = $booking->customer ? $booking->customer->name : null;
        }
    }

    private function processFuel($t, $vendor)
    {
        $fuel = $t->fuel;
        if ($fuel && (!$vendor || $fuel->vendor_name == $vendor)) {
            $t->org_id = $fuel->vendor_name;
            $t->date = $fuel->created_at;
            $t->dateof = $fuel->date ? date("Y-m-d H:i:s", strtotime($fuel->date)) : null;
            $t->vc = $fuel->vendor ? $fuel->vendor->name : null;
        }
    }

    private function processParts($t, $vendor)
    {
        $partsInvoice = $t->partsInvoice;
        if ($partsInvoice && (!$vendor || $partsInvoice->vendor_id == $vendor)) {
            $t->org_id = $partsInvoice->vendor_id;
            $t->date = $t->dateof = $partsInvoice->created_at;
            $t->vc = $partsInvoice->vendor ? $partsInvoice->vendor->name : null;
        }
    }

    private function processWorkOrder($t, $vendor)
    {
        $workOrder = $t->workorders;
        if ($workOrder && (!$vendor || $workOrder->vendor_id == $vendor)) {
            $t->org_id = $workOrder->vendor_id;
            $date_stuff = $workOrder->required_by ?? $workOrder->created_at;
            $t->date = $t->dateof = $date_stuff ? date("Y-m-d H:i:s", strtotime($date_stuff)) : null;
            $t->vc = $workOrder->vendor ? $workOrder->vendor->name : null;
        }
    }

    public function bulk_store(Request $request)
    {
        //1->Remaining,2->Completed,3->Shortage(Nonpayment due to some issues),4->Driver's Fault
        $totalMoney = array_sum($request->price);
        // dd(base64_decode($request->total));
        // dd($request->all(),$totalMoney);

        // Getting Vendor/Customer id
        $someArray = $request->ids;
        $singleId = reset($someArray);
        $trom = Transaction::find($singleId);
        
        $cvid = null;
        $cv_name = '';
        
        if ($trom->param_id == 18) { //booking
            $booking = Bookings::find($trom->from_id);
            $cvid = $booking->customer_id;
            $cv_name = 'booking';
        }
        elseif ($trom->param_id == 20) { //fuel
            $fuel = FuelModel::find($trom->from_id);
            $cvid = $fuel->vendor_name;
            $cv_name = 'fuel';
        }
        elseif ($trom->param_id == 26) { //Parts Invoice
            $parts = PartsInvoice::find($trom->from_id);
            $cvid = $parts->vendor_id;
            $cv_name = 'parts';
        }
        elseif ($trom->param_id == 28) { //Work Orders
            $workOrder = WorkOrders::find($trom->from_id);
            $cvid = $workOrder->vendor_id;
            $cv_name = 'work_order';
        }

        // Bulk Pay
        $bulk_id = BulkPayment::create([
            'bank_id' => $request->bank_account,
            'cv_id' => $cvid,
            'cv_name' => $cv_name,
            'date' => date('Y-m-d H:i:s', strtotime($request->date)),
            'amount' => $totalMoney
        ])->id;

        // dd($request->all());
        // dd($request->ids);
        $ogMoney = $request->hid;
        $ogPrice = $request->price;
        $ogMethod = $request->method;
        $ogReference = $request->reference;
        $ogAction = $request->faulty;
        $ogRemarks = $request->remarks;
        $ogDate = date('Y-m-d H:i:s', strtotime($request->date));
        foreach ($ogPrice as $id => $price) {
            // dd($ogPrice[$id]);
            $owed = $ogMoney[$id];
            $entered = $price;
            $remaining = bcdiv($owed - $entered, 1, 2);
            // dd(var_dump($remaining));
            $inEx = [
                'transaction_id' => $id,
                'payment_method' => $ogMethod,
                'ref_no' => $ogReference,
                'date' => $ogDate,
                'amount' => $entered,
                'remaining' => $remaining,
                'remarks' => $ogRemarks[$id]
            ];
            IncomeExpense::create($inEx);

            if (in_array($ogAction[$id], [1, 2]) || $remaining == 0) {
                $is_complete = $remaining == 0 ? 1 : 2;
                $trash = [
                    'is_completed' => $is_complete,
                    'bank_id' => $request->bank_account
                ];
                // $trash = $remaining==0 ? ['is_completed'=>1] : ['is_completed'=>2];
                Transaction::where('id', $id)->update($trash);
            }

            if (in_array($ogAction[$id], [3, 4]) && $remaining > 0) { //3->to self,4->driver's fault

                //To 3 or 4 IncomeExpense[3->Shortage/Non-Payment,4->Driver Liabiliity]
                // $fx = $ogAction[$id]==3 ? 1 : 2; //1-Revised Rate, 2-Driver Liability for db table//This logic fails if we get more options on bulk pay other than "3","4"
                $fxc = $ogAction[$id] == 3 ? "Shortage/Non-Payment" : "Driver Liability";
                //Down below we are just keeping the record of the amount which were non-payable or driver liable

                // $inExp = [
                //     'transaction_id'=>$id,
                //     'payment_method'=>$ogMethod,
                //     'date'=>$ogDate,
                //     'amount'=>$remaining,
                //     'remaining'=>'0.00',
                //     'faulty'=>$ogAction[$id],
                //     'remarks'=>$fxc
                // ];
                // IncomeExpense::create($inExp); //Adding the remaining as faulty

                $transaction = Transaction::find($id);
                $total = $transaction->total;
                //$new_total increases/decreases in the foreach loop so its not reliable to set Transaction->total value. 
                //so we use $new_total1 to set the total value of Transaction
                $new_total = $new_total1 = $total - $remaining;

                $incomeExp = IncomeExpense::where('transaction_id', $id)->orderBy('id', 'asc')->get();
                foreach ($incomeExp as $ie) {
                    $ie_amount = $ie->amount;
                    $ie_remaining = bcdiv($new_total - $ie_amount, 1, 2);
                    IncomeExpense::find($ie->id)->update(['remaining' => $ie_remaining]);
                    $new_total = $ie_remaining; //setting the value for next iterations
                }
                //Update transaction total price
                Transaction::where('id', $id)->update(['is_completed' => 1, 'total' => $new_total1]);
                /////////////    NOTE         ////////////////////////////////
                //Added a new table (Shortage Tables) which will store the values of bookings which got rejected/shortage then we can use from_id in Transaction table
                if ($ogAction[$id] == 3) //3->Shortage/Non-Payment add to dummy non-payble head
                {
                    $shortageData = [
                        'from_id' => $id, //transaction_id
                        'param_id' => 31, //SELF
                        'fault_type' => $ogAction[$id],
                        'amount' => bcdiv($remaining, 1, 2),
                        'total_amount' => bcdiv($total, 1, 2),
                        'pay_type' => 48, //Non-Payment
                        'date' => date("Y-m-d H:i:s"),
                        'remarks' => $fxc
                    ];
                    $from_id = ShortageModel::create($shortageData)->id; //shortage_id
                }
                if ($ogAction[$id] == 4) //Will be added to daily advance
                {
                    $driver_id = Bookings::find($transaction->from_id)->driver_id;
                    $dailyTrans = [
                        'driver_id' => $driver_id,
                        'date' => date('Y-m-d H:i:s'),
                        'amount' => $remaining,
                        'remarks' => $fxc,
                        'from_id' => $id
                    ];
                    $from_id = DailyAdvance::create($dailyTrans)->id;
                }
                $bankAcct = $ogAction[$id] == 3 ? null : $request->bank_account; //self or other account
                $param_id = $ogAction[$id] == 3 ? 31 : 32; //Shortage/Non-Payment or Liability
                // $pay_method = $ogAction[$id]==3 ? 16 : 17; // Payment Method
                $pay_type = $ogAction[$id] == 3 ? 48 : 24; // Payment Method Non-Payment or Debit

                //New Transaction Add
                $account['from_id'] = $from_id; //daily advance id or self
                $account['type'] = $pay_type; //Credit or Debit
                $account['bank_id'] = $bankAcct; //Bank Account
                $account['param_id'] = $param_id; //From id from BankTransaction or Daily Advance
                $account['from_transaction'] = $id; //current transaction id
                $account['is_completed'] = 1;
                $account['total'] = $remaining;


                $transid = Transaction::create($account)->id;
                $trash = ['type' => $pay_type, 'from' => $param_id, 'id' => $transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                //New IncomeExpense Add
                $income['transaction_id'] = $transid;
                $income['payment_method'] = $ogMethod; //DD
                $income['ref_no'] = $ogReference; //Reference
                $income['date'] = $ogDate;
                $income['amount'] = $remaining;
                $income['remaining'] = '0.00';
                $income['remarks'] = $ogRemarks[$id];

                IncomeExpense::create($income);

                //Change frieght and add OLD FRIEGHT PRICE
                $booking_id = Transaction::find($id)->from_id;
                $bookingData = Bookings::find($booking_id);
                if (!empty($bookingData)) {
                    $oldFrieghtPrice = $bookingData->getMeta('total_price');
                    $bookingData->total_price = $new_total1;
                    $bookingData->old_frieght_price = $oldFrieghtPrice;
                    $bookingData->save();
                }


                //Add to Transaction History table
                $hist = [
                    'trans_id' => $id,
                    'new_transid' => $transid,
                    'fault_type' => $ogAction[$id],
                    'full_amount' => $total,
                    'new_amount' => $new_total1, //because $new_total value changes in foreach
                    'revised_amount' => $remaining,
                    'date' => date('Y-m-d H:i:s')
                ];
                TransactionHistory::create($hist);
            }
            if (in_array($ogAction[$id], [1, 2]))
                $user_action = $remaining == 0 ? 1 : 2;
            else
                $user_action = $ogAction[$id];
            //Bulk List
            $bulkList = [
                'bulk_id' => $bulk_id,
                'bank_id' => $request->bank_account,
                'transaction_id' => $id,
                'amount' => $entered,
                'fault' => $user_action,
                'comment' => $ogRemarks[$id]
            ];
            BulkList::create($bulkList);
        }
        return redirect()->route('bulk_pay.manage');
    }

    public function bulk_manage()
    {
        $index['bulkpays'] = BulkPayment::orderBy('id', 'DESC')->get();
        foreach ($index['bulkpays'] as $bk) {
            $bk->trash = $bk->bulk_list;
        }
        // dd($index);
        return view('bank_account.manage_bulkpay', $index);
    }

    public function bulk_viewevent(Request $request)
    {
        $index['bulk'] = BulkPayment::find($request->id);
        $index['bulk_list'] = BulkList::where('bulk_id', $request->id)->get();
        $tt = BulkList::where('bulk_id', $request->id)->first()->transaction_id;
        $trom = Transaction::find($tt);
        if ($trom->param_id == 18) //booking
            $cvname = Bookings::find($trom->from_id)->customer->name;
        elseif ($trom->param_id == 20) //fuel
            $cvname = FuelModel::find($trom->from_id)->vendor->name;
        elseif ($trom->param_id == 26) //Parts
            $cvname = PartsInvoice::find($trom->from_id)->vendor->name;
        $index['bulk']->name = $cvname;
        $index['faults'] =  [1 => 'Remaining', 2 => 'Completed', 3 => 'To Self', 4 => "Driver's Fault"];

        foreach ($index['bulk_list'] as $bl) {
            $bl->trash = Transaction::find($bl->transaction_id);
        }

        // dd($index);
        return view('bank_account.bulk_viewevent', $index);
    }
    public function compareValues(Request $request)
    {
        if ((float) $request->act >= (float) $request->ent) $resp = ['status' => false];
        else $resp = ['status' => true];
        return response()->json($resp);
    }
    public function getAmount(Request $request)
    {
        $summed = Helper::properDecimals(array_sum($request->sum));
        $response = ['total' => $summed];
        // dd($response);
        return response()->json($response);
        // dd($request->all());
    }

    public function add_amount($id)
    {
        $index['current'] = BankAccount::find($id);
        $index['is_self'] = [1 => 'Yes', 2 => 'No'];
        $index['banks'] = BankAccount::where('id', '!=', 1)->pluck('bank', 'id');
        // dd($index);
        return view('bank_account.add_more', $index);
    }

    public function addAmountStore(Request $request)
    {
        // dd($request->all());
        if (!$request->has('is_self') || $request->is_self == 2 || $request->is_self == null || $request->is_self == 0) {
            $is_self = null;
            $bank = $request->bank; //Other Banks
        } else {
            $is_self = $request->bank;
            $bank = 1; //Cash or SELF
        }

        $type = 23; //credit
        $formatted_date = date("Y-m-d", strtotime($request->date));
        $bankTrans = [
            'bank_id' => $bank,
            'refer_bank' => $is_self,
            'amount' => (float) $request->amount,
            'date' => $formatted_date,
            'remarks' => $request->remarks
        ];
        //Add to Bank Transactions
        $id = BankTransaction::create($bankTrans)->id;

        // Insert in Transactions
        $bankTransaction['from_id'] = $id; //Booking Id
        $bankTransaction['type'] = $type; // Credit 
        $bankTransaction['bank_id'] = $bank; // Bank ID 
        $bankTransaction['param_id'] = 30; //Deposit
        $bankTransaction['total'] = (float) $request->amount; //Total Amount

        $transid = Transaction::create($bankTransaction)->id;
        $trash = ['type' => $type, 'from' => 30, 'id' => $transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

        // Insert in IncomeExpense
        $income['transaction_id'] = $transid;
        $income['payment_method'] = 17; //DD
        $income['date'] = $formatted_date;
        $income['amount'] = (float) $request->amount;
        $income['remaining'] = 0;
        $income['remarks'] = $request->remarks;

        IncomeExpense::create($income);
        return redirect()->route('bank-account.index');
    }

    public function deposit()
    {
        // dd(12);
        $index['deposits'] = BankTransaction::orderBy('id', 'DESC')->get();
        // dd($index);
        return view('bank_account.deposit', $index);
    }

    public function deposit_edit($id)
    {
        $index['deposit'] = BankTransaction::find($id);
        $index['is_self'] = [1 => 'Yes', 2 => 'No'];
        $index['banks'] = BankAccount::where('id', '!=', 1)->pluck('bank', 'id');
        $index['bankSelect'] =  empty($index['deposit']->refer_bank) ? $index['deposit']->bank_id : $index['deposit']->refer_bank;
        // dd($index);
        return view('bank_account.deposit_edit', $index);
    }

    public function deposit_save($id, Request $request)
    {
        // dd($id);
        // $deposit = BankTransaction::find($id);
        // dd($request->all());

        $trash = Transaction::where(['from_id' => $id, 'param_id' => 30]);
        $transa_id = $trash->first()->id;
        $trash->update(['total' => $request->amount, 'bank_id' => $request->is_self == 1 ? 1 : $request->bank]);

        IncomeExpense::where('transaction_id', $transa_id)->update(['amount' => $request->amount]);

        //if($request->is_self==1) refer_bank = bank_id, main_bankid = 1
        //else refer_bank = null, main_bankid = bank_id

        $toUpdate = [
            'bank_id' => $request->is_self == 1 ? 1 : $request->bank,
            'refer_bank' => $request->is_self == 1 ? $request->bank : null,
            'amount' => $request->amount,
            'date' => date("Y-m-d", strtotime($request->date)),
            'remarks' => $request->remarks
        ];

        BankTransaction::where('id', $id)->update($toUpdate);
        return redirect()->back();
    }

    public function deposit_view_event($id)
    {
        $index['deposit'] = BankTransaction::where('id', $id)->first();
        // dd($index['deposit']);
        return view('bank_account.deposit_view', $index);
    }
}
