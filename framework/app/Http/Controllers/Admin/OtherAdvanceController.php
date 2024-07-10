<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtherAdvanceRequest;
use App\Model\BankAccount;
use App\Model\IncomeExpense;
use Illuminate\Http\Request;
use App\Model\OtherAdvance;
use App\Model\User;
use DB;
use App\Model\Params;
use App\Model\Transaction;
use Helper;
use Auth;

class OtherAdvanceController extends Controller
{

    public function index()
    {

        $index['data'] = OtherAdvance::orderBy('id', 'DESC')->get();
        // dd($index);
        return view('other_advance.index', $index);
    }

    public function create()
    {
        $index['drivers'] = User::where('user_type', 'D')->pluck('name', 'id');
        $index['bank'] = BankAccount::select('id', DB::raw("CONCAT(bank,'(',account_no,')') as name"))->pluck('name', 'id');
        $index['method'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
        // dd($index);
        return view('other_advance.create', $index);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // $formData = $request->all();
        // unset($formData["_token"]);
        $formData = request()->validate([
            "driver_id" => 'required',
            "bank" => 'required',
            "method" => 'required',
            "ref_no" => 'required',
            "date" => 'required',
            "amount" => 'required',
            "remarks" => 'required',
        ]);
        // dd($errors);
        $formData['date'] = !empty($request->date) ? Helper::ymd($request->date) : null;
        // unset($formData["_method"]);
        // dd($formData);
        $id = OtherAdvance::create($formData)->id;

        //transaction
        $accountTransa['from_id'] = $id; //Vehicle Docs ID
        $accountTransa['type'] = 24; // Debit 
        $accountTransa['bank_id'] = $request->bank; // Bank ID
        $accountTransa['param_id'] = 43; //From Other Advances
        $accountTransa['total'] = $request->amount;

        $transid = Transaction::create($accountTransa)->id;

        $trash = ['type' => 24, 'from' => 43, 'id' => $transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

        $expense['transaction_id'] = $transid;
        $expense['payment_method'] = $request->method;
        $expense['date'] = !empty($request->date) ? Helper::ymd($request->date) : null;
        $expense['amount'] =  $request->amount;
        $expense['remaining'] = 0;
        $expense['remarks'] = $request->remarks;

        IncomeExpense::create($expense);

        return redirect()->route('other-advance.index');
    }

    public function edit(OtherAdvance $otherAdvance)
    {
        // dd($leave);
        $index['drivers'] = User::where('user_type', 'D')->pluck('name', 'id');
        $index['bank'] = BankAccount::select('id', DB::raw("CONCAT(bank,'(',account_no,')') as name"))->pluck('name', 'id');
        $index['method'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
        $index['otherAdvance'] = $otherAdvance;
        return view('other_advance.edit', $index);
    }

    public function update(OtherAdvance $otherAdvance, OtherAdvanceRequest $request)
    {
        //Code
        // dd($otherAdvance);
        // dd($otherAdvance,$request->all());
        // dd($request->all());
        // $formData = request()->validate([
        //     "driver_id" => 'required',
        //     "bank" => 'required',
        //     "method" => 'required',
        //     "ref_no" => 'required',
        //     "date" => 'required',
        //     "amount" => 'required',
        //     "remarks" => 'required',
        // ]);
        $formData['date'] = !empty($request->date) ? Helper::ymd($request->date) : null;
        OtherAdvance::where('id', $otherAdvance->id)->update($formData);

        $accountTransa['bank_id'] = $request->bank; // Bank ID
        $accountTransa['total'] = $request->amount;

        $transa = Transaction::where(['from_id' => $otherAdvance->id, 'param_id' => 43]);
        $transa->update($accountTransa);
        IncomeExpense::where('transaction_id', $transa->first()->id)->orderBy('id', 'desc')->first()->update(['amount' => $request->amount]);
        return redirect()->back();
    }

    public function destroy(Leave $leave)
    {
        //Code
        $leave->delete();
        return redirect()->back();
    }

    public function get_remarks($ids)
    {
        // dd($ids);
        $drivers = explode(",", $ids);
        $users =  User::where('user_type', 'D')->whereIn('id', $drivers)->get();
        // dd($users->toArray());
        return view('leaves.remarks', compact('users'));
    }

    public function view_event(Request $request)
    {
        // dd($request->id);
        $index['adv'] = OtherAdvance::find($request->id);
        $index['adjusts'] = $index['adv']->adjust_advance;
        // dd($index);
        return view('other_advance.view_event', $index);
    }
}
