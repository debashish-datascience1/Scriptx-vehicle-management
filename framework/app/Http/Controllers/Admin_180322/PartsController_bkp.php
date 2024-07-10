<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartsRequest;
use App\Model\PartsCategoryModel;
use App\Model\PartsModel;
use App\Model\PartsDetails;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\PartStock;
use App\Model\Vendor;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PartsController extends Controller
{

    public function index()
    {
        
        $index['data'] = PartsModel::orderBy('id', 'desc')->get();
        
        // dd($index);
        return view("parts.index", $index);
        // $allproducts=DB::table('products')->join('cat_subcatmappin','products.cat_subcat_id','=','cat_subcatmappin.id')->join('categories','cat_subcatmappin.category_id','=','categories.id')->join('sub_categories','cat_subcatmappin.subcategory_id','=','sub_categories.id')->select('products.*','sub_categories.name as subcategory','categories.name as category')->get();
        // $index['data']=DB::table('parts')->join('parts_details','parts.id','=','parts_details.parts_id')->join('vendors','parts.vendor_id','=','vendors.id')->join('parts_category','parts_details.parts_category','=','parts_category.id')->select('parts.*','parts_details.*','parts.id as partsid','vendors.*','parts_category.name as catname')->get();
        // return view("parts.index", $index);
    }


    public function create()
    {
        $data['vendors'] = Vendor::pluck('name','id');  
        $data['categories'] = PartsCategoryModel::pluck('name','id');
        $data['is_gst'] = [1=>'Yes',2=>'No'];      
        return view("parts.create", $data);
    }

    public function destroy(Request $request)
    {
       // dd('delete');
    //    dd($request->all());
        // $form_id = DB::table('transactions')
        //      ->select('id')
        //      ->where('from_id','=', $request->get('id'))
        //      ->where('param_id','=', 26)
        //      ->where('type','=', 24)
        //      ->firstOrFail()->id;
        $query = Transaction::where(['from_id'=> $request->get('id'),'param_id'=> 26,'type'=> 24]);
        $from_id = empty($query->first()) ? null :  $query->first()->from_id;
        // dd($from_id);
        if(!empty($from_id)){
            IncomeExpense::where('transaction_id',$from_id)->delete();
            Transaction::where('param_id',26)->where('type','=', 24)->where('from_id',$request->get('id'))->delete();
        }
        PartsDetails::where('parts_id',$request->get('id'))->delete();
        PartsModel::find($request->get('id'))->delete();
        
        return redirect()->route('parts.index');
    }

    public function get_parts_form(Request $request)
    {  
        // dd($request->all());
        $vendors = Vendor::pluck('name','id');
        $categories = PartsCategoryModel::pluck('name','id');   
        return view('parts.parts_form',compact('vendors', 'categories'));      
    }


    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = uniqid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $x = PartsModel::find($id)->update([$field => $fileName1]);

    }

    public function edit($id)
    {
        // dd($id);
        $index['data'] = PartsModel::whereId($id)->first();
        $index['parts_details'] = PartsDetails::where('parts_id',$id)->get();
        $index['vendors'] = Vendor::pluck('name','id');  
        $index['categories'] = PartsCategoryModel::pluck('name','id');
        $index['is_gst'] = [1=>'Yes',2=>'No'];
        
        return view("parts.edit", $index);
    }

    public function stock($id)
    {
        $data['data'] = PartStock::wherePart_id($id)->get();
        return view("parts.stocks", $data);
    }

    public function update(PartsRequest $request)
    {
        // dd($request->all());
        $part_id = $request->id;
        $billno = $request->billno;
        $vendor_id = $request->vendor_id;
        $cash_payment = $request->cash_payment;
        $cheque_draft = $request->cheque_draft;
        $cheque_draft_amount = $request->cheque_draft_amount;
        $cheque_draft_date = $request->cheque_draft_date;
        //delete previous part_details containing $part_id
        PartsDetails::where('parts_id',$part_id)->delete();
        
        //Insert new data
        foreach($request->category_id as $key=>$val){
            $details_data = [
                'parts_id'=>$part_id,
                'title'=>$request->title[$key],
                'parts_category'=>$request->category_id[$key],
                'number'=>$request->number[$key],
                'manufacture'=>$request->manufacturer[$key],
                'status'=>$request->status[$key],
                'unit_cost'=>$request->unit_cost[$key],
                'availability'=>$request->availability[$key],
                'quantity'=>$request->stock[$key],
                'date_of_purchase'=>array_key_exists($key,$request->dateofpurchase) ? date('Y-m-d',strtotime($request->dateofpurchase[$key])) : null ,
                'total'=>$request->total[$key],
            ];
            PartsDetails::create($details_data);
        }

        
        // GST Calculations
		$total = empty($request->subtotal) ? null : (float) $request->subtotal;
		$cgst = empty($request->cgst) ? null : (float) $request->cgst;
		$sgst = empty($request->sgst) ? null : (float) $request->sgst;
		$isgst = !empty($request->is_gst) ? $request->is_gst : null;

		// $total = $price;
		// dd($total);
		// CGST Calculation
		if(!empty($cgst)){
			$cgstval = ($cgst/100) * $total;
		}else{
			$cgstval = null;
		}

		// SGST Calculation
		if(!empty($sgst)){
			$sgstval = ($sgst/100) * $total;
		}else{
			$sgstval = null;
		}
		// dd($cgstval);
        
		$grandtotal = $total + $cgstval + $sgstval;
		// $abc = [$cgstval,$sgstval,$total,$grandtotal];
		// dd($abc);
		
        $partmod = [
            'is_gst' => $isgst,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'cgst_amt' => Helper::properDecimals($cgstval),
            'sgst_amt' => Helper::properDecimals($sgstval),
            'sub_total' => Helper::properDecimals($total),
            'grand_total' => Helper::properDecimals($grandtotal),
            'user_id' => Auth::user()->id,
            'billno' => $billno,
            'vendor_id' => $vendor_id,
            'cash_amount' => $cash_payment,
            'chq_draft_number' => $cheque_draft,
            'chq_draft_amount' => $cheque_draft_amount,
            'chq_draft_date' => empty($cheque_draft_date) ? null : date('Y-m-d',strtotime($cheque_draft_date)),
        ];
        PartsModel::where('id',$part_id)->update($partmod);

        //Update Transaction,IncomeExpense
        //Program from here
        
        if(!empty($grandtotal) && !empty($total)){
            $transa = Transaction::where(['from_id'=>$part_id,'param_id'=>26]);
            // dd($transa->get());
            $cash_p = empty($cash_payment) ? 0 : $cash_payment;
            $transa->update(['total'=>$grandtotal]);
            $trns_id = $transa->first()->id;
            // dd($trns_id);
            
            $rem = $grandtotal - $cash_p;
            IncomeExpense::where('transaction_id',$trns_id)->update(['amount'=>$cash_p,'remaining'=>$rem]);
		}

        return redirect()->back();
    } 
    public function store(PartsRequest $request)
    {
        // dd($request->all());
        $parts = new PartsModel();
        // GST Calculations
		$total = empty($request->subtotal) ? null : (float) $request->subtotal;
		$cgst = empty($request->cgst) ? null : (float) $request->cgst;
		$sgst = empty($request->sgst) ? null : (float) $request->sgst;
		$isgst = !empty($request->is_gst) ? $request->is_gst : null;

		// $total = $price;
		// dd($total);
		// CGST Calculation
		if(!empty($cgst)){
			$cgstval = ($cgst/100) * $total;
		}else{
			$cgstval = null;
		}

		// SGST Calculation
		if(!empty($sgst)){
			$sgstval = ($sgst/100) * $total;
		}else{
			$sgstval = null;
		}
		// dd($cgstval);
        $chq_date = $request->get('cheque_draft_date');
		$grandtotal = $total + $cgstval + $sgstval;
		// $abc = [$cgstval,$sgstval,$total,$grandtotal];
		// dd($abc);
		$parts->is_gst = $isgst;
		$parts->cgst = $cgst;
		$parts->sgst = $sgst;
		$parts->cgst_amt = Helper::properDecimals($cgstval);
		$parts->sgst_amt = Helper::properDecimals($sgstval);
        $parts->sub_total = Helper::properDecimals($total);
		$parts->grand_total = Helper::properDecimals($grandtotal);
        $parts->user_id = Auth::user()->id;
        $parts->billno = $request->get('billno');
        $parts->vendor_id = $request->get('vendor_id');
        $parts->cash_amount = $request->get('cash_payment');
        $parts->chq_draft_number = $request->get('cheque_draft');
        $parts->chq_draft_amount = $request->get('cheque_draft_amount');
        $parts->chq_draft_date = empty($chq_date) ? null : date('Y-m-d',strtotime($chq_date));
        $parts->save();
        $id = $parts->id;
       // dd($request->availability);
        

       
        foreach($request->category_id as $k=>$v){
            // print($request->number[$k]);
            $eachArr = [
                'parts_id'=>$id,
                'title'=>$request->title[$k],
                'parts_category'=>$request->category_id[$k],
                'unit_cost'=>$request->unit_cost[$k],
                'availability'=>$request->availability[$k],
                'quantity'=>$request->stock[$k],
                'total'=>$request->total[$k],
            ];
            PartsDetails::create($eachArr);
        }

        $id_trans = Transaction::create([
            'type' => 24,
            'from_id' => $id,
            'param_id' => 26,
            'total' => $total,
        ])->id;
        $trash = ['type'=>24,'from'=>26,'id'=>$id_trans];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::where('id',$id_trans)->update(['transaction_id'=>$transaction_id]);

        //get cash amount
        $payemnt_method="16";
        if($request->cash_payment!="")
        {
           $Amount=$request->cash_payment;
        }
        //get chq/draft amount
        if($request->cheque_draft_amount!="")
        {
            $Amount=$request->cheque_draft_amount;
            $payemnt_method="17";
        }
        $Amount = !isset($Amount) ? 0 : $Amount;
        
           $totalTrans = array_sum($request->total);
        
           $rem_incExp=$totalTrans-$Amount;
           $id_incomeExp = IncomeExpense::create([
            'transaction_id' => $id_trans,
            'payment_method' => $payemnt_method, 
            'date' => date('Y-m-d H:i:s'),
            'amount' => $Amount,
            'remaining' => $rem_incExp, 
        ]);

        return redirect()->route("parts.index");
    }

    public function bulk_delete(Request $request)
    {
        
        $from_id=Transaction::select('id')->where('param_id',26)->where('type',24)->whereIn('from_id', $request->get('ids'))->get();

        IncomeExpense::whereIn('transaction_id',$from_id)->delete();
        Transaction::where('param_id',26)->where('type',24)->whereIn('from_id',$request->get('ids'))->delete();
        PartsDetails::whereIn('parts_id',$request->get('ids'))->delete();
        PartsModel::whereIn('id',$request->get('ids'))->delete();
        return back();
        
    }

    public function add_stock(Request $request)
    {
        $part = PartsModel::find($request->part_id);
        $part->stock = $part->stock + $request->stock;
        $part->save();
        return back();
    }
    

    public function view_event($id){
        $index['parts']=PartsDetails::where("parts_id",$id)->get();
        $index['row']=PartsModel::where("id",$id)->first();
        return view('parts.view_event',$index);
    }
}
