<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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

class OtherAdvanceController extends Controller {

    public function index()
    {
        
        $index['data'] = OtherAdvance::orderBy('id','DESC')->get();
        // dd($index);
        return view('other_advance.index',$index);
    }

    public function create(){
        $index['drivers'] = User::where('user_type','D')->pluck('name','id');
        $index['bank'] = BankAccount::select('id',DB::raw("CONCAT(bank,'(',account_no,')') as name"))->pluck('name','id');
        $index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
        // dd($index);
        return view('other_advance.create',$index);
    }

    public function store(Request $request){
        // dd($request->all());
        $formData = $request->all();
        unset($formData["_token"]);
        // unset($formData["_method"]);

        $id = OtherAdvance::create($formData)->id;

        //transaction
        $accountTransa['from_id'] = $id; //Vehicle Docs ID
        $accountTransa['type'] = 24;// Debit 
        $accountTransa['bank_id'] = $request->bank;// Bank ID
        $accountTransa['param_id'] = 43; //From Other Advances
        $accountTransa['total'] = $request->amount;

        $transid = Transaction::create($accountTransa)->id;

        $trash = ['type'=>24,'from'=>43,'id'=>$transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);

        $expense['transaction_id'] = $transid;
        $expense['payment_method'] = $request->method;
        $expense['date'] = date("Y-m-d H:i:s");
        $expense['amount'] =  $request->amount;
        $expense['remaining'] = 0;
        $expense['remarks'] = $request->remarks;

        IncomeExpense::create($expense);

        return redirect()->route('other-advance.index');
    }

    public function edit(OtherAdvance $otherAdvance){
        // dd($leave);
        $index['drivers'] = User::where('user_type','D')->pluck('name','id');
        $index['bank'] = BankAccount::select('id',DB::raw("CONCAT(bank,'(',account_no,')') as name"))->pluck('name','id');
        $index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $index['otherAdvance'] = $otherAdvance;
        return view('other_advance.edit',$index);
    }

    public function update(OtherAdvance $otherAdvance,Request $request){
        //Code
        // dd($otherAdvance);
        // dd($otherAdvance,$request->all());
        $formData = $request->all();
        unset($formData['_token']);
        unset($formData['_method']);
        OtherAdvance::where('id',$otherAdvance->id)->update($formData);

        $accountTransa['bank_id'] = $request->bank;// Bank ID
        $accountTransa['total'] = $request->amount;

        $transa = Transaction::where(['from_id'=>$otherAdvance->id,'param_id'=>43]);
        $transa->update($accountTransa);
        IncomeExpense::where('transaction_id',$transa->first()->id)->orderBy('id','desc')->first()->update(['amount'=>$request->amount]);
        return redirect()->back();
    }

    public function destroy(Leave $leave){
        //Code
       $leave->delete();
       return redirect()->back();
    }

    public function get_remarks($ids){
        // dd($ids);
        $drivers = explode(",", $ids);
        $users =  User::where('user_type','D')->whereIn('id',$drivers)->get();
        // dd($users->toArray());
        return view('leaves.remarks',compact('users'));
    }

    public function view_event(Request $request){
        // dd($request->id);
        $index['adv'] = OtherAdvance::find($request->id);
        $index['adjusts'] = $index['adv']->adjust_advance;
        // dd($index);
        return view('other_advance.view_event',$index);
    }

    public function report(){
        $data['drivers'] = User::where('user_type','D')->pluck('name','id');
        $data['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $data['status'] = [1=>"Completed",2=>"In Progress","3"=>"Not Yet Done"];
        $data['request'] = null;
        // dd($data);
        return view('other_advance.report',$data);
    }

    public function report_post(Request $request){
        // dd($request->all());
        $driver_id = $request->get('driver_id');
        $method = $request->get('method');
        $status = $request->get('status');
        
        if($request->get('date1')==null)
			$start = OtherAdvance::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = OtherAdvance::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

        //where condition
        $where = array();
        if(!empty($driver_id)) $where['driver_id'] = $driver_id;
        if(!empty($method)) $where['method'] = $method;
        if(!empty($status)) $where['is_adjusted'] = $status==3 ? null :$status;
        // dd($where);
        if(!empty($driver_id) || !empty($method) || !empty($status)) {
            $otherAdvance = OtherAdvance::where($where)->whereBetween('date', [$start, $end]);
        }else{
            $otherAdvance = OtherAdvance::whereBetween('date', [$start, $end]);
        }

        $data['result'] = "";
        $data['other_adv'] = $otherAdvance->orderBy("id","DESC")->get();
        $data['drivers'] = User::where('user_type','D')->pluck('name','id');
        $data['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $data['status'] = [1=>"Completed",2=>"In Progress","3"=>"Not Yet Done"];
		$data['request'] = $request->all();
        $data['date1'] = $start;
		$data['date2'] = $end;
        // dd($data);
        return view('other_advance.report', $data);
    }

    public function report_print(Request $request){
        // dd($request->all());
        $driver_id = $request->get('driver_id');
        $method = $request->get('method');
        $status = $request->get('status');
        
        if($request->get('date1')==null)
			$start = OtherAdvance::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = OtherAdvance::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

        //where condition
        $where = array();
        if(!empty($driver_id)) $where['driver_id'] = $driver_id;
        if(!empty($method)) $where['method'] = $method;
        if(!empty($status)) $where['is_adjusted'] = $status==3 ? null :$status;
        // dd($where);
        if(!empty($driver_id) || !empty($method) || !empty($status)) {
            $otherAdvance = OtherAdvance::where($where)->whereBetween('date', [$start, $end]);
        }else{
            $otherAdvance = OtherAdvance::whereBetween('date', [$start, $end]);
        }

        $data['result'] = "";
        $data['other_adv'] = $otherAdvance->orderBy("id","DESC")->get();
        $data['drivers'] = User::where('user_type','D')->pluck('name','id');
        $data['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $data['status'] = [1=>"Completed",2=>"In Progress","3"=>"Not Yet Done"];
		$data['request'] = $request->all();
        $data['from_date'] = $start;
		$data['to_date'] = $end;
        // dd($data);
        return view('other_advance.print-report', $data);
    }

}