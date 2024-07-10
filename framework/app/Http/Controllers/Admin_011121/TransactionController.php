<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Bookings;
use App\Model\Params;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\AdvanceDriver;
use App\Model\Payroll;
use App\Model\FuelModel;
use App\Model\DailyAdvance;
use App\Model\PartsDetails;
use App\Model\WorkOrders;
use App\Model\BankAccount;
use App\Model\BankTransaction;
use App\Model\VehicleDocs;
use DB;
use Log;
use Helper;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(){
        $param_id = "transaction";
        Helper::housekeeping($param_id);
        $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
        $object = (array)json_decode($put);
        $collection = Transaction::hydrate($object);
        // dd($collection);
        $data['transactions'] = $collection->flatten();   // get rid of unique_id_XXX
        return view('transactions.index',$data);
    }
    // public function index()
    // {
    //     // dd("Index");
    //     // $index['transactions'] = Transaction::orderBy('id','ASC')->get();
    //     // foreach($index['transactions'] as $k=>$t){
    //     //     $transaction = Transaction::where(['from_id'=>$t->from_id,'param_id'=>$t->param_id]);
    //     //     if($transaction->exists()){
    //     //         // dd($transaction->first());
    //     //         $income = IncomeExpense::where('transaction_id',$transaction->first()->id)->withTrashed()->first();
    //     //         // dd($transaction->first()->id);
    //     //         // dd($income);
    //     //         // empty($income) ? dd($transaction->get()) : false;
    //     //         $t->method = $income->payment_method;
    //     //         $t->amt = $income->amount;
    //     //         $exdata = IncomeExpense::where('transaction_id',$t->id)->orderBy('id','DESC')->take(1);
    //     //         // if($t->advance_for!=21){
    //     //             $t->rem = $exdata->exists() ? $exdata->first() : null;
    //     //         // }else{ 
    //     //         //     $t->rem = null;
    //     //         // }

    //     //         // if($k==(count($index['transactions'])-1)){
    //     //         //     $t->prev = 0;
    //     //         //     $t->grandtotal = $t->prev-$t->total;
    //     //         // }else{
    //     //         //     $prev_arr[] = $index['transactions'][$k+1]->total; 
    //     //         //     $t->prev = array_sum($prev_arr);
    //     //         //     $t->grandtotal = $t->prev+$t->total;
    //     //         // } 
              

    //     //         if($k==0){
    //     //             $t->prev = 0;
    //     //             $t->grandtotal = $t->prev-$t->total;
    //     //         }else{
    //     //             $t->prev = $index['transactions'][$k-1]->grandtotal;
    //     //             if($t->type==23)
    //     //                 $t->grandtotal = $index['transactions'][$k-1]->grandtotal+$t->total;
    //     //             else
    //     //                 $t->grandtotal = $index['transactions'][$k-1]->grandtotal-$t->total;
    //     //         }
                  
    //     //     }else{
    //     //         $t->method = null;
    //     //         $t->amt = null;
    //     //         $t->rem = null;
    //     //         $t->prev = 0;
    //     //     }
    //     //     // dd($t);
    //     // }
    //     // $reversed = $index['transactions']->reverse();
    //     // $index['transactions'] = $reversed;
    //     // dd($index);
    //     return view('transactions.index-datatable');
    // }
    public function getTransactionList(Request $request){
        $index['transactions'] = Transaction::orderBy('id','ASC')->get();
        foreach($index['transactions'] as $k=>$t){
            $transaction = Transaction::where(['from_id'=>$t->from_id,'param_id'=>$t->param_id]);
            if($transaction->exists()){
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id',$transaction->first()->id)->withTrashed()->first();
                // dd($transaction->first()->id);
                // dd($income);
                // empty($income) ? dd($transaction->get()) : false;
                $t->method = $income->payment_method;
                $t->amt = $income->amount;
                $exdata = IncomeExpense::where('transaction_id',$t->id)->orderBy('id','DESC')->take(1);
                // if($t->advance_for!=21){
                    $t->rem = $exdata->exists() ? $exdata->first() : null;
                // }else{ 
                //     $t->rem = null;
                // }

                // if($k==(count($index['transactions'])-1)){
                //     $t->prev = 0;
                //     $t->grandtotal = $t->prev-$t->total;
                // }else{
                //     $prev_arr[] = $index['transactions'][$k+1]->total; 
                //     $t->prev = array_sum($prev_arr);
                //     $t->grandtotal = $t->prev+$t->total;
                // } 
              

                if($k==0){
                    $t->prev = 0;
                    $t->grandtotal = $t->prev-$t->total;
                }else{
                    $t->prev = $index['transactions'][$k-1]->grandtotal;
                    if($t->type==23)
                        $t->grandtotal = $index['transactions'][$k-1]->grandtotal+$t->total;
                    else
                        $t->grandtotal = $index['transactions'][$k-1]->grandtotal-$t->total;
                }
                  
            }else{
                $t->method = null;
                $t->amt = null;
                $t->rem = null;
                $t->prev = 0;
            }
            // dd($t);
        }
        $reversed = $index['transactions']->reverse();
        // Datatable
        $draw = $request->get('draw');
		$start = $request->get('start');
		$rowperpage = $request->get('length'); //Rows display per page


        $reversed = $reversed->slice($start)->take($rowperpage);

        $totalRecords = $totalRecordswithFilter = Transaction::orderBy('id','ASC')->count();
        $classes = [
            18 => "badge badge-success where_from",
            19 => "badge badge-info where_from",
            20 => "badge badge-fuel where_from",
            25 => "badge badge-driver-adv where_from",
            26 => "badge badge-parts where_from",
            27 => "badge badge-refund where_from",
            28 => "badge badge-info where_from",
            29 => "badge badge-starting-amt where_from",
            30 => "badge badge-deposit where_from",
            31 => "badge badge-revised where_from",
            32 => "badge badge-liability where_from"
        ];
        $data_arr = array();
        foreach($reversed as $rv){
            $id = $rv->id;
            $checkbox = "<input type='checkbox' name='ids[]' value='$id' class='checkbox' id='chk$id' onclick='checkcheckbox();'>";

            $trashColmn = "<a class='badge badge-viwevent vevent' data-id='$rv->id' data-toggle='modal' data-target='#viewModal' title='$rv->transaction_id'>
                $rv->transaction_id
                </a><br>";
            $trashColmn .= "<strong>";
            $trashColmn .= !empty($rv->rem->date) ? Helper::getCanonicalDate($rv->rem->date) : Helper::getCanonicalDate($rv->rem->created_at);
            $trashColmn .= "</strong>";
            $class_name = $classes[$rv->param_id];
            $param_lbl = !empty($rv->params) ? $rv->params->label : 'N/A';
            if($rv->param_id==18){
                $fromColmn = "<a class='badge badge-success where_from' data-id='$rv->id' data-toggle='modal' data-target='#whereModal' title='$param_lbl'>$param_lbl</a>";
                $fromColmn .= "<br>";
                  
                if($rv->advance_for==21){
                  $advfor_lbl = !empty($rv->advancefor) ? $rv->advancefor->label : 'N/A';
                  $fromColmn .= "<a class='badge badge-warning advance_for' data-id='$rv->id' data-toggle='modal' data-target='#advanceForModal' title='$advfor_lbl'>$advfor_lbl</a>";
                }
            }else{
                $partsw = $rv->param_id ==26 ? "data-partsw='$rv->id'" : null;
                $fromColmn = "<a class='$class_name' $partsw data-id='$rv->id' data-toggle='modal' data-target='#whereModal' title='$param_lbl'>$param_lbl</a>";
            }

            $methodColmn = $rv->pay_method->label;
            $prevColmn = number_format($rv->prev,2,'.','');
            $totalColmn = $rv->total." "."<br>";
            $paytypeColmn = $rv->pay_type->label;
            if($rv->type==23)
                $totalColmn.="<span class='badge badge-success'>$paytypeColmn</span>";
            elseif($rv->type==24)
                $totalColmn.="<span class='badge badge-danger'>$paytypeColmn</span>";
            else
                $totalColmn.="<span class='badge badge-danger'>N/A</span>";
            
            $remainingColmn = number_format($rv->rem->remaining,2,'.','');
            $gtotalColmn = number_format($rv->grandtotal,2,'.','');

            $data_arr[] = [
                'id'=>$checkbox,
                'trash_id'=>$trashColmn,
                'from'=>$fromColmn,
                'method'=>$methodColmn,
                'prev'=>$prevColmn,
                'total'=>$totalColmn,
                'remaining'=>$remainingColmn,
                'gtotal'=>$gtotalColmn
            ];
        }
        $response = [
			"draw"=> intval($draw),
			"iTotalRecords"=> $totalRecords,
			"iTotalDisplayRecords" => $totalRecordswithFilter,
			"aaData" => $data_arr
		];
		// return redirect()->json($response);
		echo json_encode($response);
		exit;
    }

    public function view_event($id){
        $index['transactions'] = Transaction::find($id);
        $index['income'] = IncomeExpense::where('transaction_id',$index['transactions']->id)->orderBy('id','ASC')->first();
        $index['previous'] = Transaction::where('id','<',$index['transactions']->id)->exists() ? Transaction::where('id','<',$index['transactions']->id)->orderBy('id','DESC')->first() : 0;
        $flatten = $this->getDetails($id)->flatten();
        $index['getdet'] = $flatten;
        // dd($index);
        return view('transactions.view_event',$index);
    }

    public function getDetails($id){
        $tids = Transaction::where('id','<=',$id)->select('id')->get();
        foreach($tids as $id){
            $ids[] = $id->id;
        }
        $index['transactions'] = Transaction::whereIn('id',$ids)->orderBy('id','ASC')->get();
        foreach($index['transactions'] as $k=>$t){
            $transaction = Transaction::where(['from_id'=>$t->from_id,'param_id'=>$t->param_id]);
            if($transaction->exists()){
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id',$transaction->first()->id)->first();
                // dd($transaction->first()->id);
                $t->method = $income->payment_method;
                $t->amt = $income->amount; 
                if($k==0){
                    $t->prev = 0;
                    $t->grandtotal = $t->prev-$t->total;
                }else{
                    $t->prev = $index['transactions'][$k-1]->grandtotal;
                    if($t->type==23)
                        $t->grandtotal = $index['transactions'][$k-1]->grandtotal+$t->total;
                    else
                        $t->grandtotal = $index['transactions'][$k-1]->grandtotal-$t->total;
                }
            }else{
                $t->method = null;
                $t->amt = null;
                $t->prev = 0;
            }
        }
        return $index['transactions']->reverse()->take(1);
        // dd($index);
    }

    public function view_bank_event($id){
        $index['transactions'] = Transaction::find($id);
        $index['income'] = IncomeExpense::where('transaction_id',$index['transactions']->id)->orderBy('id','ASC')->first();
        $index['previous'] = Transaction::where('id','<',$index['transactions']->id)->exists() ? Transaction::where('id','<',$index['transactions']->id)->orderBy('id','DESC')->first() : 0;
        $flatten = $this->getBankTransDetails($id)->flatten();
        $index['getdet'] = $flatten;
        // dd($index);
        return view('transactions.view_bank_event',$index);
    }

    public function getBankTransDetails($id){
        $tids = Transaction::where('id','<=',$id)->select('id')->get();
        $bankArr = [];
        foreach($tids as $id){
            $ids[] = $id->id;
        }
        $index['transactions'] = Transaction::whereIn('id',$ids)->orderBy('id','ASC')->get();
        foreach($index['transactions'] as $k=>$t){
            $transaction = Transaction::where(['from_id'=>$t->from_id,'param_id'=>$t->param_id]);
            if($transaction->exists()){
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id',$transaction->first()->id)->withTrashed()->first(); 

                $t->method = $income->payment_method;
                $t->amt = $income->amount;
                $exdata = IncomeExpense::where('transaction_id',$t->id)->orderBy('id','DESC')->take(1);
                
                $t->rem = $exdata->exists() ? $exdata->first() : null;
                
                // Bank ID
                $bank_id = ($t->bank_id==null || $t->bank_id==0) ? 0 : $t->bank_id;
                // $bankArr[$bank_id][] = $t->total;

                if(array_key_exists($bank_id,$bankArr)){
                    $prev = $bankArr[$bank_id][count($bankArr[$bank_id])-1]['prev'];
                    $prev_total = $bankArr[$bank_id][count($bankArr[$bank_id])-1]['gtotal'];
                    $gtotal = $t->type==23 ? $prev_total+$t->total : $prev_total-$t->total;
                    $bankArr[$bank_id][] = [
                        'prev'=>$prev_total,
                        'amount'=>$t->total,
                        'gtotal'=>$gtotal
                    ];
                }else{
                    $gtotal = $t->type==23 ? 0+$t->total : 0-$t->total;
                    $bankArr[$bank_id][] = [
                        'prev'=>0,
                        'amount'=>$t->total,
                        'gtotal'=>$gtotal
                    ];
                }
                $t->prev =  $bankArr[$bank_id][count($bankArr[$bank_id])-1]['prev'];
                $t->grandtotal =  $bankArr[$bank_id][count($bankArr[$bank_id])-1]['gtotal'];
                  
            }else{
                $t->method = null;
                $t->amt = null;
                $t->rem = null;
                $t->prev = 0;
            }
            // dd($t);
        }
        return $index['transactions']->reverse()->take(1);
    }

    public function adjust($id){
        // dd(Transaction::find($id));
        $transaction = Transaction::find($id);
        $index['incomes'] = IncomeExpense::where('transaction_id',$transaction->id)->get();
        $index['remaining'] = IncomeExpense::where('transaction_id',$transaction->id)->orderBy('id','DESC')->take(1)->exists() ? IncomeExpense::where('transaction_id',$transaction->id)->orderBy('id','DESC')->take(1)->first() : null;
        $index['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
        $index['transaction'] = $transaction;
        // dd($index);
        
        return view('transactions.adjust',$index);
    }

    public function where_from($id){
        $index['transaction'] = $tr = Transaction::find($id);
        // dd($tr);
        if($tr->param_id==18){
            $index['data'] = Bookings::where('id',$tr->from_id)->withTrashed()->first();
            // dd($index['data']->getMeta());
            $index['params'] = Params::where('id',Bookings::where('id',$tr->from_id)->withTrashed()->first()->getMeta('loadtype'))->first('label');
            if($index['params']->label=="")
            $index['params']->label = "N/A";
        }else if($tr->param_id==19){
            $index['payroll'] = Payroll::where('id',$tr->from_id)->withTrashed()->first();
        }else if($tr->param_id==20){
            $index['fuel'] = FuelModel::where('id',$tr->from_id)->withTrashed()->first();
        }else if($tr->param_id==25 || $tr->param_id==32){
            $index['advance'] = DailyAdvance::where('id',$tr->from_id)->withTrashed()->first();
        }else if($tr->param_id==26){
            $index['parts'] = PartsDetails::where('parts_id',$tr->from_id)->get();
        }else if($tr->param_id==27){
            $bookingid = AdvanceDriver::find($tr->from_id)->booking_id;
            $index['advances'] = AdvanceDriver::where('booking_id',$bookingid)->get();
            $index['advTotal'] = AdvanceDriver::where('booking_id',$bookingid)->sum('value');
        }else if($tr->param_id==28){
            $index['workOrders'] = WorkOrders::where('id',$tr->from_id)->withTrashed()->get();
        }else if($tr->param_id==29){
            $index['bankAccounts'] = BankAccount::where('id',$tr->from_id)->withTrashed()->get();
        }else if($tr->param_id==30 || $tr->param_id==31){
            $index['bankTransactions'] = BankTransaction::where('id',$tr->from_id)->withTrashed()->get();
        }else if($tr->param_id==35){
            $index['row'] = VehicleDocs::where('id',$tr->from_id)->withTrashed()->first();
        }
        
        // dd($index['advance']->driver);
        
        // dd($index);
        return view('transactions.where_from',$index);
    }

    public function advance_for($id){
        $index['transaction'] = $tr = Transaction::find($id);
        if($tr->param_id==18 && $tr->advance_for==21){
            $bookid = Bookings::where('id',$tr->from_id)->withTrashed()->first()->id;
            $advDriver = AdvanceDriver::where('booking_id',$bookid);
            $index['advance'] = $advDriver->exists() ? $advDriver->get() : null;
            $index['gtotal'] = $advDriver->exists() ? $advDriver->sum('value') : 0;
        }
        // dd($index);
        return view('transactions.advance_for',$index);
    }

    public function report(){
        //dd('zzzzzzzz');
        $index['from'] = Params::where('code','PaymentFrom')->pluck('label','id');
        $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['request'] = null;
        // dd($index);
        
        return view('transactions.reportDebitCredit',$index);
    }

    // public function report_post(Request $request){
    //      dd($request->all());
    //     $from = $request->get('from');
    //     $method = $request->get('method');
    //     $from_date = $request->get('from_date');
    //     $to_date = $request->get('to_date'); 
        
    //     // $t = Transaction::where(function($q){
    //     //     return $q->from('income_expense')
    //     //             ->where('transaction_id',$q->id)
    //     //             ->orderBy('id','DESC')->take(1);

    //     // })->get();
    //     // dd($t);
    //      // Getting the dates
    //     if(empty($from_date)){
    //         $from_date = IncomeExpense::orderBy('date','ASC')->take(1)->first()->date;
    //     }else $from_date = null;

    //     if(empty($to_date)){
    //         $to_date = IncomeExpense::orderBy('date','DESC')->take(1)->first()->date;
    //     }else $to_date = null;
    //     // dd($from_date." ".$to_date);
    //     $transaction  = Transaction::get();

    //     foreach($transaction as $t){
    //         $t->date = !empty($t->incExp) ? $t->incExp->date : null;
    //         $t->method = !empty($t->incExp) ? $t->incExp->payment_method : null;
    //     }
    //     if(empty($from) && empty($method)){
    //         $trasReport = $transaction;
    //     }elseif(empty($method)){
    //         // dd($from);
    //         $trasReport = $transaction->where('param_id',$from);
    //         // dd($trasReport);
    //     }elseif(empty($from)){
    //         // dd(1234);
    //         $trasReport = $transaction->where('payment_method',$method);
    //     }else{
    //         // dd(1455);
    //         $trasReport = $transaction->where(['from_id'=>$from,'payment_method'=>$method]);
    //     }
    //     // dd($trasReport);
    //     // Check date
    //     if(empty($from_date) || empty($to_date)){
    //         $data = $trasReport;
    //     }else{
    //         $data = $trasReport->whereBetween('date',[$from_date,$to_date]);
    //     }

    //     // $filtered = $data->filter(function($value,$key){
    //     //     return $value->whereBetween('date',[$from_date,$to_date]);
    //     // });
    //     // $index['transaction'] = $transaction;
    //     $index['data'] = $data;
    //     // $index['trash'] = $trash;
    //     $index['request'] = $request->all();
    //     dd($index);
    //     // return redirect()->route('accounting.report');
    // }

    public function report_post(Request $request){
         //dd($request->all());
        $from = $request->get('from');
        $payment_type = $request->get('payment_type');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date'); 
        
        $from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
		$to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;

        if(empty($from) && empty($payment_type))
            {
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where('deleted_at',null)->sum('total');
            }
        elseif(empty($from))
            {
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('type',$payment_type)->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['type'=>$payment_type,'deleted_at'=>null])->sum('total');
            }
        elseif(empty($payment_type))
            {
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('param_id',$from)->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>$from,'deleted_at'=>null])->sum('total');
            }
		else{
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>$from,'type'=>$payment_type])->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>$from,'type'=>$payment_type,'deleted_at'=>null])->sum('total');
            }

        $transaction  = $transmodel->get();
        
        
        $index['transaction'] = $transaction;
        $index['from'] = Params::where('code','PaymentFrom')->pluck('label','id');
        $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['result'] = "";
        $index['sumoftotal']=$sumoftotal;
        $index['request'] = $request->all();
        //dd($index);
        return view('transactions.reportDebitCredit',$index);

    }
    public function report_print(Request $request){
        $from = $request->get('from');
        $payment_type = $request->get('payment_type');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date'); 
        
        $from_date = empty($from_date) ? Transaction::orderBy('created_at','asc')->take(1)->first('created_at')->created_at : $from_date;
		$to_date = empty($to_date) ? Transaction::orderBy('created_at','desc')->take(1)->first('created_at')->created_at : $to_date;

        if(empty($from) && empty($payment_type))
            {
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where('deleted_at',null)->sum('total');
            }
        elseif(empty($from))
            {
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('type',$payment_type)->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['type'=>$payment_type,'deleted_at'=>null])->sum('total');
            }
        elseif(empty($payment_type))
            {
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where('param_id',$from)->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>$from,'deleted_at'=>null])->sum('total');
            }
		else{
            $transmodel = Transaction::whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>$from,'type'=>$payment_type])->select('*');
            $sumoftotal=DB::table('transactions')->whereBetween('created_at',[$from_date,$to_date])->where(['param_id'=>$from,'type'=>$payment_type,'deleted_at'=>null])->sum('total');
            }

        $transaction  = $transmodel->get();
        $index['transaction'] = $transaction;
        $index['from'] = Params::where('code','PaymentFrom')->pluck('label','id');
        $index['payment_type'] = Params::where('code','PaymentType')->pluck('label','id');
        $index['sumoftotal']=$sumoftotal;
        $index['result'] = "";
        $index['request'] = $request->all();
        return view('transactions.reportDebitCredit-print',$index);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        // $arr =['type'=>24,'from'=>27,'id'=>25];
        // dd(Helper::transaction_id($arr));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // if(empty($request->adjDate))
        //     return redirect()->withError()
        $chk = IncomeExpense::where('transaction_id',$request->trans_id);
        // dd($request->trans_id);
        // dd($chk->exists());
        // dd(count($chk->get()));
        if($chk->exists() && count($chk->get())==1 && !$request->has('adjDate')){
                // $request->adjAmountPrev;
                $rem = $request->remainingAmt;
                $rms = $rem-$request->adjAmountPrev[0];
                $incomeid = $request->income_id[0];
                $incomePrev = [
                    'transaction_id'=>$request->trans_id,
                    'payment_method'=>$request->adjTypePrev[0],
                    'date'=>date("Y-m-d H:i:s",strtotime($request->adjDatePrev[0])),
                    'amount'=>$request->adjAmountPrev[0],
                    'remaining'=>$rms,
                    'remarks'=>empty($request->adjRemarksPrev) ? IncomeExpense::find($incomeid)->remarks : $request->adjRemarksPrev,
                ];
                IncomeExpense::find($incomeid)->update($incomePrev);
        }else{
            $remainingAmount = $request->remainingAmt;
            
            foreach($request->adjDate as $k=>$item){
                // dd($item);
                $rem = (float)$remainingAmount-(float)$request->adjAmount[$k];
                $income = [
                    'transaction_id'=>$request->trans_id,
                    'payment_method'=>$request->adjType[$k],
                    'date'=>$item,
                    'amount'=>$request->adjAmount[$k],
                    'remaining'=>$rem,
                    'remarks'=>$request->adjRemarks[$k],
                ];
                $remainingAmount = $rem;
                IncomeExpense::create($income);
            }
        }
        // dd($income);
        return redirect()->route('accounting.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function reportbank(){
        // dd("Index");
        $index['transactions'] = Transaction::orderBy('id','ASC')->get();
        $bankArr = [];
        foreach($index['transactions'] as $k=>$t){
            $transaction = Transaction::where(['from_id'=>$t->from_id,'param_id'=>$t->param_id]);
            if($transaction->exists()){
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id',$transaction->first()->id)->withTrashed()->first();
                // dd($transaction->first()->id);
                // dd($income);
                // empty($income) ? dd($transaction->get()) : false;

                //Add by bank 


                $t->method = $income->payment_method;
                $t->amt = $income->amount;
                $exdata = IncomeExpense::where('transaction_id',$t->id)->orderBy('id','DESC')->take(1);
                
                $t->rem = $exdata->exists() ? $exdata->first() : null;
                
                // Bank ID
                $bank_id = ($t->bank_id==null || $t->bank_id==0) ? 0 : $t->bank_id;
                // $bankArr[$bank_id][] = $t->total;

                if(array_key_exists($bank_id,$bankArr)){
                    $prev = $bankArr[$bank_id][count($bankArr[$bank_id])-1]['prev'];
                    $prev_total = $bankArr[$bank_id][count($bankArr[$bank_id])-1]['gtotal'];
                    $gtotal = $t->type==23 ? $prev_total+$t->total : $prev_total-$t->total;
                    $bankArr[$bank_id][] = [
                        'prev'=>$prev_total,
                        'amount'=>$t->total,
                        'gtotal'=>$gtotal
                    ];
                }else{
                    $gtotal = $t->type==23 ? 0+$t->total : 0-$t->total;
                    $bankArr[$bank_id][] = [
                        'prev'=>0,
                        'amount'=>$t->total,
                        'gtotal'=>$gtotal
                    ];
                }
                $t->prev =  $bankArr[$bank_id][count($bankArr[$bank_id])-1]['prev'];
                $t->grandtotal =  $bankArr[$bank_id][count($bankArr[$bank_id])-1]['gtotal'];
                  
            }else{
                $t->method = null;
                $t->amt = null;
                $t->rem = null;
                $t->prev = 0;
            }
            // dd($t);
        }
        $reversed = $index['transactions']->reverse();
        $index['transactions'] = $reversed;
        // dd($bankArr);
        // dd($index);
        return view('transactions.index-bank',$index);
        
        // return view('transactions.index-bank-datatable');
    }
    public function getTransactionBankList(Request $request){
        // dd("Index");
        // Log::info(json_encode("abc"));
        $index['transactions'] = Transaction::orderBy('id','ASC')->get();
        $bankArr = [];
        foreach($index['transactions'] as $k=>$t){
            $transaction = Transaction::where(['from_id'=>$t->from_id,'param_id'=>$t->param_id]);
            if($transaction->exists()){
                // dd($transaction->first());
                $income = IncomeExpense::where('transaction_id',$transaction->first()->id)->withTrashed()->first();

                $t->method = $income->payment_method;
                $t->amt = $income->amount;
                $exdata = IncomeExpense::where('transaction_id',$t->id)->orderBy('id','DESC')->take(1);
                
                $t->rem = $exdata->exists() ? $exdata->first() : null;
                
                // Bank ID
                $bank_id = ($t->bank_id==null || $t->bank_id==0) ? 0 : $t->bank_id;
                

                if(array_key_exists($bank_id,$bankArr)){
                    $prev = $bankArr[$bank_id][count($bankArr[$bank_id])-1]['prev'];
                    $prev_total = $bankArr[$bank_id][count($bankArr[$bank_id])-1]['gtotal'];
                    $gtotal = $t->type==23 ? $prev_total+$t->total : $prev_total-$t->total;
                    $bankArr[$bank_id][] = [
                        'prev'=>$prev_total,
                        'amount'=>$t->total,
                        'gtotal'=>$gtotal
                    ];
                }else{
                    $gtotal = $t->type==23 ? 0+$t->total : 0-$t->total;
                    $bankArr[$bank_id][] = [
                        'prev'=>0,
                        'amount'=>$t->total,
                        'gtotal'=>$gtotal
                    ];
                }
                $t->prev =  $bankArr[$bank_id][count($bankArr[$bank_id])-1]['prev'];
                $t->grandtotal =  $bankArr[$bank_id][count($bankArr[$bank_id])-1]['gtotal'];
                  
            }else{
                $t->method = null;
                $t->amt = null;
                $t->rem = null;
                $t->prev = 0;
            }
            // dd($t);
        }
        $reversed = $index['transactions']->reverse();
        // Log::info($reversed);
        // $index['transactions'] = $reversed;

        // Datatable
        $draw = $request->get('draw');
		$start = $request->get('start');
		$rowperpage = $request->get('length'); //Rows display per page


        $reversed = $reversed->slice($start)->take($rowperpage);

        $totalRecords = $totalRecordswithFilter = Transaction::orderBy('id','ASC')->count();
        $classes = [
            18 => "badge badge-success where_from",
            19 => "badge badge-info where_from",
            20 => "badge badge-fuel where_from",
            25 => "badge badge-driver-adv where_from",
            26 => "badge badge-parts where_from",
            27 => "badge badge-refund where_from",
            28 => "badge badge-info where_from",
            29 => "badge badge-starting-amt where_from",
            30 => "badge badge-deposit where_from",
            31 => "badge badge-revised where_from",
            32 => "badge badge-liability where_from"
        ];
        $data_arr = array();
        
        foreach($reversed as $rv){
            $id = $rv->id;
            $checkbox = "<input type='checkbox' name='ids[]' value='$id' class='checkbox' id='chk$id' onclick='checkcheckbox();'>";
            $trashColmn = "<a class='badge badge-viwevent vevent' data-id='$rv->id' data-toggle='modal' data-target='#viewModal' title='$rv->transaction_id'>
                $rv->transaction_id
                </a><br>";
            $trashColmn .= "<strong>";
            $trashColmn .= !empty($rv->rem->date) ? Helper::getCanonicalDate($rv->rem->date) : Helper::getCanonicalDate($rv->rem->created_at);
            $trashColmn .= "</strong>";


            $class_name = $classes[$rv->param_id];
            $param_lbl = !empty($rv->params) ? $rv->params->label : 'N/A';
            if($rv->param_id==18){
                $fromColmn = "<a class='badge badge-success where_from' data-id='$rv->id' data-toggle='modal' data-target='#whereModal' title='$param_lbl'>$param_lbl</a>";
                $fromColmn .= "<br>";
                  
                if($rv->advance_for==21){
                  $advfor_lbl = !empty($rv->advancefor) ? $rv->advancefor->label : 'N/A';
                  $fromColmn .= "<a class='badge badge-warning advance_for' data-id='$rv->id' data-toggle='modal' data-target='#advanceForModal' title='$advfor_lbl'>$advfor_lbl</a>";
                }
            }else{
                $partsw = $rv->param_id ==26 ? "data-partsw='$rv->id'" : null;
                $fromColmn = "<a class='$class_name' $partsw data-id='$rv->id' data-toggle='modal' data-target='#whereModal' title='$param_lbl'>$param_lbl</a>";
            }
            $bankName = empty($rv->bank) ? 'N/A' : $rv->bank->bank;
            $fromColmn .= "<br><label for='bank'>";
            $fromColmn .= $bankName;
            $fromColmn .= "</label>";
            $methodColmn = $rv->pay_method->label;
            $prevColmn = number_format($rv->prev,2,'.','');
            $totalColmn = $rv->total." "."<br>";
            $paytypeColmn = $rv->pay_type->label;
            if($rv->type==23)
                $totalColmn.="<span class='badge badge-success'>$paytypeColmn</span>";
            elseif($rv->type==24)
                $totalColmn.="<span class='badge badge-danger'>$paytypeColmn</span>";
            else
                $totalColmn.="<span class='badge badge-danger'>N/A</span>";
            
            $remainingColmn = number_format($rv->rem->remaining,2,'.','');
            $gtotalColmn = number_format($rv->grandtotal,2,'.','');
            $data_arr[] = [
                'id'=>$checkbox,
                'trash_id'=>$trashColmn,
                'from'=>$fromColmn,
                'method'=>$methodColmn,
                'prev'=>$prevColmn,
                'total'=>$totalColmn,
                'remaining'=>$remainingColmn,
                'gtotal'=>$gtotalColmn
            ];
        }
        // Log::info($reversed);

        $response = [
			"draw"=> intval($draw),
			"iTotalRecords"=> $totalRecords,
			"iTotalDisplayRecords" => $totalRecordswithFilter,
			"aaData" => $data_arr
		];
		// return redirect()->json($response);
		echo json_encode($response);
		exit;
    }
}