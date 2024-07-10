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
use Illuminate\Http\Request;
use Helper;

class BankAccountController extends Controller
{
    public function index(){
        // dd("index");
        $index['bankAccount'] = BankAccount::get();
        $index['is_self'] = [1=>'Yes',2=>'No'];
        $index['banks'] = BankAccount::where('id','!=',1)->pluck('bank','id');
        foreach($index['bankAccount'] as $b){
            $total = BankTransaction::where('bank_id',$b->bank_id)->sum('amount');
            $paid = Transaction::where('bank_id',$b->bank_id)->sum('total');
            $allTotal = $b->starting_amount+$total;
            $b->total = $allTotal;
            $b->remain = $allTotal-$paid;
        }
        // dd($index);
        return view('bank_account.index',$index);
    }

    public function create(){
        return view('bank_account.create');
    }

    public function store(Request $request){
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
        $trash = ['type'=>23,'from'=>29,'id'=>$transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);
        
        $income['transaction_id'] = $transid;
        $income['payment_method'] = 16; //Cash
        $income['date'] = date("Y-m-d H:i:s");
        $income['amount'] = BankAccount::find($id)->starting_amount;
        $income['remaining'] = 0;
        $income['remarks'] = null;

        IncomeExpense::create($income);
        return redirect()->route('bank-account.index');
    }

    public function edit(BankAccount $bankAccount){
        // dd($bankAccount);
        $index['bankAccount'] = $bankAccount;
        return view('bank_account.edit',$index);
    }

    public function  show(){
        //
    }

    public function update(BankAccount $bankAccount,Request $request){
        // dd($bankAccount->id);
        dd($request->all());
        $formData = $request->all();
        unset($formData['_method']);
        unset($formData['_token']);

        // Transaction
        $transa = Transaction::where(['from_id'=>$bankAccount->id,'param_id'=>29]);
        $transaid = $transa->first()->id;
        $transa->update(['total'=>$request->starting_amount]);

        // Income Expense
        IncomeExpense::where('transaction_id',$transaid)->update(['amount'=>$request->starting_amount]);
        BankAccount::where('id',$bankAccount->id)->update($formData);
        return redirect()->route('bank-account.index');
    }

    public function destroy($id){
        //
    }

    public function view_event($id){
        // dd(BankAccount::find($id));
        $index['bankAccount'] = BankAccount::find($id);
        $index['history'] = BankTransaction::where('bank_id',$id)->orderBy('id','DESC')->take(10)->get();
        return view('bank_account.view_event',$index);
    }
    public function bulk_pay(){
        // dd("pay up");
        $index['customers'] = User::where('user_type', 'C')->pluck('name','id');
        $index['vend'] = Vendor::get();
        $index['request'] = null;
        foreach($index['vend'] as $v){
            $index['vendors'][$v->id] = $v->name." ( $v->type )";
        }
        $index['vendor'] =  null;
        $index['customer'] =  null;
        $index['vendorSelect'] =  null;
        $index['customerSelect'] =  null;
        // dd($index);
        return view('bank_account.bulk_pay',$index);
    }
    public function bulk_paypost(Request $request){
        // dd($request->all());
        $customer = $request->customer;
        $vendor = $request->vendor;
        $index['transactions'] = Transaction::whereIn('param_id',[18,20,26,28])
                                ->where(function($query){
                                    $query->whereNull('is_completed')
                                        ->orWhere('is_completed',2);
                                })
                                ->where(function($q){
                                    $q->where('advance_for',22)
                                      ->orwhereNull('advance_for');
                                })->get();
                    // dd($index);
        foreach($index['transactions'] as $k=>$t){
            if($t->param_id==18){ //Booking
                $shalom = Bookings::where(['id'=>$t->from_id,'customer_id'=>$customer]);
                $t->org_id = $org = $shalom->exists() ? $shalom->first()->customer_id : null;
                $t->date = $shalom->exists() ? $shalom->first()->created_at : null;
                $t->vc = !empty($t->org_id) ? User::find($t->org_id)->name : null;
            }elseif($t->param_id==19){ //Payroll
                $t->org_id = null;
            }elseif($t->param_id==20){ //Fuel
                $shalom = FuelModel::where(['id'=>$t->from_id,'vendor_name'=>$vendor]);
                $t->org_id = $shalom->exists() ? $shalom->first()->vendor_name : null;
                $t->date = $shalom->exists() ? $shalom->first()->created_at : null;
                $t->vc = !empty($t->org_id) ? Vendor::find($t->org_id)->name : null;
            }elseif($t->param_id==26){ //Parts
                $shalom = PartsModel::where(['id'=>$t->from_id,'vendor_id'=>$vendor]);
                $t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null;
                $t->date = $shalom->exists() ? $shalom->first()->created_at : null;
                $t->vc = !empty($t->org_id) ? Vendor::find($t->org_id)->name : null;
            }elseif($t->param_id==28){ //Work Order
                $shalom = WorkOrders::where(['id'=>$t->from_id,'vendor_id'=>$vendor]);
                $t->org_id = $shalom->exists() ? $shalom->first()->vendor_id : null;
                $t->date = $shalom->exists() ? $shalom->first()->created_at : null;
                $t->vc = !empty($t->org_id) ? Vendor::find($t->org_id)->name : null;
            }
            $t->rem = $t->incExp->remaining;
            // $t->param_id==19 ?? dd($t);
        }
        // $reversed = $index['transactions']->reverse();
        // $index['transactions'] = $reversed;
        // dd($index);
        // if(!empty($customer))
        //     $filtered = $index['transactions']->where(['id'=>18,'org_id'=>$customer]);
        // elseif(!empty($vendor)){
        //     $filtered = $index['transactions']->where(['id'=>18,'org_id'=>$vendor]);
        // }else dd($vendor);
        $filtered = $index['transactions']->where('org_id','!=',null)->where('rem','!=',0);
        $index['transactions'] = $filtered;
        // dd($index);
        $index['customers'] = User::where('user_type', 'C')->pluck('name','id');
        $index['vend'] = Vendor::get();
        foreach($index['vend'] as $v){
            $index['vendors'][$v->id] = $v->name." ( $v->type )";
        }
        $index['vendor'] = $request->has('vendor') ? $request->vendor : null;
        $index['customer'] = $request->has('customer') ? $request->customer : null;
        if(empty($request->vendor) && empty($request->customer)){
            $index['vendorSelect'] = $index['customerSelect'] = null;
        }else{
            $index['vendorSelect'] = $request->has('vendor') && !empty($request->vendor) ? null : 'disabled';
            $index['customerSelect'] = $request->has('customer') && !empty($request->customer) ? null : 'disabled';
        }
        $index['result'] = "";
        if(!empty($customer))
            $index['custvend'] = User::find($customer)->name;
        elseif(!empty($vendor))
            $index['custvend'] = Vendor::find($vendor)->name;
        else
            $index['custvend'] = null;
        
        $index['bankAccounts'] = BankAccount::where('id','!=',1)->pluck('bank','id');
        $index['totalAmount'] = $index['transactions']->sum('total');
        foreach($index['transactions'] as $tr){
            $tr->remainingAmt = $tr->incExp->remaining;
        }
        $index['remm'] = $index['transactions']->sum('remainingAmt');
        $index['faulty'] = [1=>'Remaining',2=>'To Self',3=>'Driver Fault'];
        // dd($index);
        return view('bank_account.bulk_pay',$index);
    }

    public function bulk_store(Request $request){
        // dd($request->all());
        $encoded_ids = base64_encode(serialize($request->ids));
        // dd($encoded_ids);
        // dd(unserialize(base64_decode($encoded_ids)));
        $total  = (float)base64_decode($request->total);
        $amt = (float)base64_decode($request->amount);
        $paid = (float)$request->paid;
        // dd($paid);
        // dd($amt);
        
        // dd($rem);
        // static $tot = $total;
        // $price = $amt<$paid ? ;
        
        
                // dd($saved);
        //Update
        $reqids = $request->ids;
        $vals = $request->vals;
        $datepost = date('Y-m-d H:i:s',strtotime($request->date));
        if(count($reqids)>0){
            // $moneygiv = [];
            foreach($reqids as $k=>$id){
                // dd(Transaction::find($id));
                

                $singleRowZero = IncomeExpense::where(['transaction_id'=>$id,'amount'=>0]);
                $singleRowNot = IncomeExpense::where('transaction_id',$id)->where('amount','!=',0);
                $fromRemain = Transaction::find($id)->incExp;
                
                // dd($amt);
                // dd($request->all());
                // Row One and Amount 0
                if(count($reqids)==1 && $singleRowZero->exists() && count($singleRowZero->get())==1){
                    // dd(1);
                    $rem = Helper::properDecimals($amt-$paid);
                    $singleRowZero->update(['amount'=>$paid,'remaining'=>$rem,'date'=>$datepost,'remarks'=>$fromRemain->remarks]);
                    Transaction::find($id)->update(['bank_id'=>$request->bank_account,'is_completed'=>2]);
                }elseif(count($reqids)==1 && $singleRowNot->exists() && count($singleRowNot->get())==1){
                    //Row One and Amount not 0
                    // dd(2);
                    $rem = Helper::properDecimals($amt-$paid);
                    IncomeExpense::create([
                            'transaction_id'=>$id,
                            'payment_method'=>17,
                            'amount'=>$paid,
                            'remaining'=>$rem,
                            'date'=>$datepost,
                            'remarks'=>$fromRemain->remarks
                        ]);
                    Transaction::find($id)->update(['bank_id'=>$request->bank_account,'is_completed'=>2]);
                }elseif(count($reqids)>1){
                    //Multiple Rows
                    // dd(3);
                    end($reqids);
                    if($k==key($reqids)){
                        //last record
                        $prevAmount = $amt-$fromRemain->remaining;
                        $actual_paid = $paid-$prevAmount;
                        $rem = $amt-$paid;

                        if($singleRowZero->exists()){
                            $singleRowZero->update([
                                'amount'=>$actual_paid,
                                'remaining'=>$rem,
                                'date'=>$datepost,
                                'remarks'=>$fromRemain->remarks
                            ]);
                        }elseif($singleRowNot->exists()){
                            IncomeExpense::create([
                                'transaction_id'=>$id,
                                'payment_method'=>17,
                                'amount'=>$actual_paid,
                                'remaining'=>$rem,
                                'date'=>$datepost,
                                'remarks'=>$fromRemain->remarks
                            ]);
                        }else{
                            IncomeExpense::create([
                                'transaction_id'=>$id,
                                'payment_method'=>17,
                                'amount'=>$actual_paid,
                                'remaining'=>$rem,
                                'date'=>$datepost,
                                'remarks'=>$fromRemain->remarks
                            ]);
                        }
                        Transaction::find($id)->update(['bank_id'=>$request->bank_account,'is_completed'=>2]);
                    }else{
                        //other records
                        if($singleRowZero->exists()){
                            $singleRowZero->update([
                                'amount'=>$fromRemain->remaining,
                                'remaining'=>'0.00',
                                'date'=>$datepost,
                                'remarks'=>$fromRemain->remarks
                            ]);
                        }elseif($singleRowNot->exists()){
                                IncomeExpense::create([
                                    'transaction_id'=>$id,
                                    'payment_method'=>17,
                                    'amount'=>$fromRemain->remaining,
                                    'remaining'=>'0.00',
                                    'date'=>$datepost,
                                    'remarks'=>$fromRemain->remarks
                                ]);
                        }else{
                            IncomeExpense::create([
                                'transaction_id'=>$id,
                                'payment_method'=>17,
                                'amount'=>$fromRemain->remaining,
                                'remaining'=>'0.00',
                                'date'=>$datepost,
                                'remarks'=>$fromRemain->remarks
                            ]);
                        }
                        Transaction::find($id)->update(['bank_id'=>$request->bank_account,'is_completed'=>1]);  
                    }
                }else{
                    $rem = $amt-$paid;
                    if($singleRowNot->exists()){
                            IncomeExpense::create([
                                'transaction_id'=>$id,
                                'payment_method'=>17,
                                'amount'=>$paid,
                                'remaining'=>$rem,
                                'date'=>$datepost,
                                'remarks'=>$request->remarks
                            ]);
                    }
                    if($rem==0){
                        Transaction::find($id)->update(['bank_id'=>$request->bank_account,'is_completed'=>1]);
                    }
                }

                $trap = Transaction::find($id);
                if($trap->param_id==20){ //Fuel
                    FuelModel::where('id',$trap->from_id)->update(['is_paid'=>1]);
                }elseif($trap->param_id==28){ //Work Order
                    WorkOrders::where('id',$trap->from_id)->update(['is_paid'=>1]);
                }
            }
             BulkPayment::create([
                'trans_id'=>$encoded_ids,
                'bank_id'=>$request->bank_account,
                'date'=>date('Y-m-d H:i:s',strtotime($request->date)),
                'amount'=>$paid,
                'remaining'=>$rem,
                'remarks'=>$request->remarks,
            ]);

            
        }
        return redirect()->route('bulk_pay.manage');
    }

    public function bulk_manage(){
        $index['bulkpays'] = BulkPayment::orderBy('id','DESC')->get();
        foreach($index['bulkpays'] as $blk){
            $whrin = unserialize(base64_decode($blk->trans_id));
            $blk->trash = Transaction::whereIn('id',$whrin)->get();
        }
        // dd($index);
        return view('bank_account.manage_bulkpay',$index);
    }

    public function getAmount(Request $request){
        if($request->has('sum')){
            $response['total'] = number_format(array_sum($request->sum),2,'.','');
            $response['encoded'] = base64_encode($response['total']);
        }
        else{
            $response['total'] = '0.00';
            $response['encoded'] = base64_encode($response['total']);
        } 
        // dd($response);
        return response()->json($response);
        // dd($request->all());
    }

    public function add_amount($id){
        $index['current'] = BankAccount::find($id);
        $index['is_self'] = [1=>'Yes',2=>'No'];
        $index['banks'] = BankAccount::where('id','!=',1)->pluck('bank','id');
        // dd($index);
        return view('bank_account.add_more',$index);
    }

    public function addAmountStore(Request $request){
        // dd($request->all());
        if(!$request->has('is_self') || $request->is_self==2 || $request->is_self==null || $request->is_self==0){
            $is_self = null;
            $bank = $request->bank; //Other Banks
        }else{
            $is_self = $request->bank;
            $bank = 1;//Cash or SELF
        }
        
        $type=23;//credit
        $bankTrans = [
            'bank_id'=>$bank,
            'refer_bank'=>$is_self,
            'amount'=>(float)$request->amount,
            'date'=>date("Y-m-d",strtotime($request->date)),
            'remarks'=>$request->remarks
        ];
        //Add to Bank Transactions
        $id = BankTransaction::create($bankTrans)->id;

        // Insert in Transactions
        $bankTransaction['from_id'] = $id; //Booking Id
        $bankTransaction['type'] = $type;// Credit 
        $bankTransaction['bank_id'] = $bank;// Bank ID 
        $bankTransaction['param_id'] = 30; //Deposit
        $bankTransaction['total'] = (float)$request->amount; //Total Amount

        $transid = Transaction::create($bankTransaction)->id;
        $trash = ['type'=>$type,'from'=>30,'id'=>$transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);

        // Insert in IncomeExpense
        $income['transaction_id'] = $transid;
        $income['payment_method'] = 17; //DD
        $income['date'] = date("Y-m-d H:i:s");
        $income['amount'] = (float)$request->amount;
        $income['remaining'] = 0;
        $income['remarks'] = $request->remarks;

        IncomeExpense::create($income);
        return redirect()->route('bank-account.index');
    }

    public function deposit(){
        // dd(12);
        $index['deposits'] = BankTransaction::orderBy('id','DESC')->get();
        // dd($index);
        return view('bank_account.deposit',$index);
    }

    public function deposit_edit($id){
        $index['deposit'] = BankTransaction::find($id);
        $index['is_self'] = [1=>'Yes',2=>'No'];
        $index['banks'] = BankAccount::where('id','!=',1)->pluck('bank','id');
        $index['bankSelect'] =  empty($index['deposit']->refer_bank) ? $index['deposit']->id : $index['deposit']->refer_bank;
        // dd($index);
        return view('bank_account.deposit_edit',$index);
    }

    public function deposit_save($id,Request $request){
        // dd($id);
        // $deposit = BankTransaction::find($id);
        // dd($request->all());

        $trash = Transaction::where(['from_id'=>$id,'param_id'=>30]);
        $transa_id = $trash->first()->id;
        $trash->update(['total'=>$request->amount,'bank_id'=>$request->is_self==1 ? 1 : $request->bank]);
        
        IncomeExpense::where('transaction_id',$transa_id)->update(['amount'=>$request->amount]);

        //if($request->is_self==1) refer_bank = bank_id, main_bankid = 1
        //else refer_bank = null, main_bankid = bank_id

        $toUpdate = [
            'bank_id'=> $request->is_self==1 ? 1 : $request->bank,
            'refer_bank'=> $request->is_self==1 ? $request->bank : null,
            'amount' => $request->amount,
            'date' => date("Y-m-d",strtotime($request->date)),
            'remarks' => $request->remarks
        ];

        BankTransaction::where('id',$id)->update($toUpdate);
        return redirect()->back();
    }

    public function deposit_view_event($id){
        $index['deposit'] = BankTransaction::where('id',$id)->first();
        // dd($index['deposit']);
        return view('bank_account.deposit_view',$index);
    }
}

?>