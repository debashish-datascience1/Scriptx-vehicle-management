<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\BankAccount;
use App\Model\IncomeExpense;
use App\Model\OtherAdjust;
use Illuminate\Http\Request;
use App\Model\OtherAdvance;
use App\Model\User;
use DB;
use App\Model\Params;
use App\Model\Transaction;
use Helper;
use Auth;

class OtherAdjustController extends Controller {

    public function index()
    {
        
        $index['data'] = OtherAdjust::orderBy('id','DESC')->get();
        // dd($index);
        return view('other_advance.index',$index);
    }

    public function adjust(Request $request){
        // dd($request->id);
        $transa = Transaction::where(['from_id'=>$request->id,'param_id'=>43]);
        $index['others'] = $others = OtherAdjust::where('other_id',$request->id)->get();
        $index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $index['type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['transaction'] = $transa->exists() ? $transa->first() : null;
        $index['adv']= $adv = OtherAdvance::find($request->id);
        $index['remaining'] = $adv->amount-$others->sum("amount");
        $index['bank'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"),'id')->pluck('name','id');
        // dd($index);
        return view('other_advance.other_adjust',$index);
    }

    public function addmore(Request $request){
        $index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $index['type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['bank'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"),'id')->pluck('name','id');
        return view('other_advance.addmore',$index);
    }

    public function calculate(Request $request){
        $array["entered"] = array_sum($request->value);
        $totalLend =  OtherAdvance::find($request->id)->amount;
        $array["remain"] = $totalLend-$array["entered"];
        $array["status"] = $array["remain"]>=0 ? true : false;
        return response()->json($array);
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
        // $formData = $request->all();
        // unset($formData["_token"]);
        // unset($formData["_method"]);
        $oth_id = $request->otherAdvance;
        // $adjusted = OtherAdjust::where('other_id',$oth_id)->sum('amount');
        // dd($adjusted);
        foreach($request->adjHead as $k=>$adj){
            $adjustArray = [
                'other_id'=>$oth_id,
                'head'=>ucwords(strtolower($adj)),
                'amount'=>$request->adjAmount[$k],
                'ref_no'=>strtoupper(strtolower($request->adjRef[$k])),
                'method'=>$request->adjMethod[$k],
                'type'=>$request->adjType[$k],
                'bank_id'=>$request->adjBank[$k],
                'date'=>$request->adjDate[$k],
                'remarks'=>$request->adjRemarks[$k],
            ];
            $id = OtherAdjust::create($adjustArray)->id;

            //for refund and credit
            if($request->adjType[$k]==23){//credit then add to self/cash
                $accountTransa['from_id'] = $id; //Advance Refund
                $accountTransa['type'] = 23;// Debit 
                $accountTransa['bank_id'] = $request->adjBank[$k];// Bank ID
                $accountTransa['param_id'] = 44; //From Other Advances Refund
                $accountTransa['total'] = $request->adjAmount[$k];

                $transid = Transaction::create($accountTransa)->id;

                $trash = ['type'=>23,'from'=>44,'id'=>$transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);

                $expense['transaction_id'] = $transid;
                $expense['payment_method'] = $request->adjMethod[$k];
                $expense['date'] = date("Y-m-d H:i:s");
                $expense['amount'] =  $request->adjAmount[$k];
                $expense['remaining'] = 0;
                $expense['remarks'] = $request->adjRemarks[$k];

                IncomeExpense::create($expense);
            }
        }

        $taken = OtherAdvance::find($oth_id)->amount;
        $adjusted = OtherAdjust::where('other_id',$oth_id)->sum('amount');
        $remaining = $taken-$adjusted;
        $is_adjusted = $remaining==0 ? 1 : 2;
        OtherAdvance::where('id',$oth_id)->update(['is_adjusted'=>$is_adjusted]);

        return redirect()->route('other-advance.index');
    }

    public function show(){

    }

    public function edit(OtherAdjust $otherAdjust){
        // dd($otherAdjust);
        // $index['drivers'] =  User::where('user_type','D')->pluck('name','id');
        // $index['leave'] = $otherAdjust;
        // return view('leaves.edit',$index);
    }

    public function update(OtherAdjust $otherAdjust,Request $request){
        //Code
        // dd($request->all());
        // dd($otherAdjust);
        
        // $otherAdjust->is_present = $request->is_present;
        // $otherAdjust->remarks = $request->remarks;
        // $otherAdjust->save();
        // return redirect()->back();
    }

    public function destroy(OtherAdjust $otherAdjust){
        //Code
    //    $otherAdjust->delete();
    //    return redirect()->back();
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
        $data['heads'] = OtherAdjust::groupBy('head')->pluck('head','head');
        $data['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $data['types'] = Params::where('code','PaymentType')->pluck('label','id');
        $data['banks'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"),'id')->pluck('name','id');
        $data['request'] = null;
        // dd($data);
        return view('other_adjust.report',$data);
    }

    public function report_post(Request $request){
        // dd($request->all());
        $head = $request->get('head');
        $method = $request->get('method');
        $ref_no = $request->get('ref_no');
        $type = $request->get('type');
        $bank_id = $request->get('bank_id');
        
        if($request->get('date1')==null)
			$start = OtherAdjust::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = OtherAdjust::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

        //where condition
        $where = array();
        if(!empty($head)) $where['head'] = $head;
        if(!empty($method)) $where['method'] = $method;
        if(!empty($type)) $where['type'] = $type;
        if(!empty($bank_id)) $where['bank_id'] = $bank_id;
        
        // dd($where);
        if(!empty($head) || !empty($method) || !empty($type) || !empty($bank_id)) {
            $otherAdjust = OtherAdjust::where($where)->whereBetween('date', [$start, $end]);
        }else{
            $otherAdjust = OtherAdjust::whereBetween('date', [$start, $end]);
        }
        $otherAdjust->where('ref_no','LIKE',"%$ref_no%");
        $data['result'] = "";
        $data['other_adjsqls'] = $otherAdjust->orderBy("id","DESC")->toSql();
        $data['other_adj'] = $otherAdjust->orderBy("id","DESC")->get();
        $data['heads'] = OtherAdjust::groupBy('head')->pluck('head','head');
        $data['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $data['types'] = Params::where('code','PaymentType')->pluck('label','id');
        $data['banks'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"),'id')->pluck('name','id');
		$data['request'] = $request->all();
        $data['date1'] = $start;
		$data['date2'] = $end;
        // dd($data);
        return view('other_adjust.report', $data);
    }

    public function report_print(Request $request){
        $head = $request->get('head');
        $method = $request->get('method');
        $ref_no = $request->get('ref_no');
        $type = $request->get('type');
        $bank_id = $request->get('bank_id');
        
        if($request->get('date1')==null)
			$start = OtherAdjust::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = OtherAdjust::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

        //where condition
        $where = array();
        if(!empty($head)) $where['head'] = $head;
        if(!empty($method)) $where['method'] = $method;
        if(!empty($type)) $where['type'] = $type;
        if(!empty($bank_id)) $where['bank_id'] = $bank_id;
        
        // dd($where);
        if(!empty($head) || !empty($method) || !empty($type) || !empty($bank_id)) {
            $otherAdjust = OtherAdjust::where($where)->whereBetween('date', [$start, $end]);
        }else{
            $otherAdjust = OtherAdjust::whereBetween('date', [$start, $end]);
        }
        $otherAdjust->where('ref_no','LIKE',"%$ref_no%");
        $data['result'] = "";
        $data['other_adjsqls'] = $otherAdjust->orderBy("id","DESC")->toSql();
        $data['other_adj'] = $otherAdjust->orderBy("id","DESC")->get();
        $data['heads'] = OtherAdjust::groupBy('head')->pluck('head','head');
        $data['methods'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $data['types'] = Params::where('code','PaymentType')->pluck('label','id');
        $data['banks'] = BankAccount::select(DB::raw("CONCAT(bank,'(',account_no,')') as name"),'id')->pluck('name','id');
		$data['request'] = $request->all();
        $data['from_date'] = $start;
		$data['to_date'] = $end;
        // dd($data);
        return view('other_adjust.print-report', $data);
    }

}