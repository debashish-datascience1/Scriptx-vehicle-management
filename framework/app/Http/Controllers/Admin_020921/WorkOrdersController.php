<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrderRequest;
use App\Model\PartsModel;
use App\Model\PartsUsedModel;
use App\Model\VehicleModel;
use App\Model\Vendor;
use App\Model\WorkOrderLogs;
use App\Model\WorkOrders;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use Helper;
use DB;
use Auth;
use Illuminate\Http\Request;

class WorkOrdersController extends Controller
{

    public function logs()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $vehicle_ids = VehicleModel::pluck('id')->toArray();
        } else {
            $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
        }
        $index['data'] = WorkOrderLogs::whereIn('vehicle_id', $vehicle_ids)->latest()->get();
        // dd(12);
        return view('work_orders.logs', $index);
    }

    public function report_vendor(){
        $workOrder = WorkOrders::orderBy('vendor_id','ASC')->groupBy('vendor_id')->get();
        foreach($workOrder as $work){
            $ids[] = $work->vendor_id;
        }
        $index['vendors'] = Vendor::whereIn('id',$ids)->pluck('name','id');
        $index['status'] = WorkOrders::groupBy('status')->pluck('status','status');
        $index['request'] = null;
        // dd($index);

        return view('work_orders.report',$index);
    }

    public function report_vendorpost(Request $request){
        // dd($request->all());
        $vendor = $request->vendor;
        $status = $request->status;
        $from_date = empty($request->from_date) ? WorkOrders::orderBy('required_by','ASC')->take(1)->first('required_by')->required_by : $request->from_date;
        $to_date = empty($request->to_date) ? WorkOrders::orderBy('required_by','DESC')->take(1)->first('required_by')->required_by : $request->to_date;

        if(empty($vendor) && empty($status))
            $workOrder = WorkOrders::whereBetween('required_by',[$from_date,$to_date]);
        else if(empty($vendor))
            $workOrder = WorkOrders::where('status',$status)->whereBetween('required_by',[$from_date,$to_date]);
        else if(empty($status))
            $workOrder = WorkOrders::where('vendor_id',$vendor)->whereBetween('required_by',[$from_date,$to_date]);
        else
            $workOrder = WorkOrders::where(['vendor_id'=>$vendor,'status'=>$status])->whereBetween('required_by',[$from_date,$to_date]);
        $vendor_ids = WorkOrders::orderBy('vendor_id','ASC')->groupBy('vendor_id')->get();
        // dd($ids);
        foreach($vendor_ids as $work){
            $ids[] = $work->vendor_id;
        }
        $index['workOrder'] = $workOrder->get();
        $index['vendors'] = Vendor::whereIn('id',$ids)->pluck('name','id');
        $index['status'] = WorkOrders::groupBy('status')->pluck('status','status');
        $index['is_vendor'] = !empty($vendor) ? true : false;
        $index['gtotal'] = $workOrder->sum('price');
        $index['result'] = "";
        $index['request'] = $request->all();
        // dd($index);
        return view('work_orders.report',$index);
    }

    public function report_vendorprint(Request $request){
        // dd($request->all());
        $vendor = $request->vendor;
        $status = $request->status;
        $from_date = empty($request->from_date) ? WorkOrders::orderBy('required_by','ASC')->take(1)->first('required_by')->required_by : $request->from_date;
        $to_date = empty($request->to_date) ? WorkOrders::orderBy('required_by','DESC')->take(1)->first('required_by')->required_by : $request->to_date;

        if(empty($vendor) && empty($status))
            $workOrder = WorkOrders::whereBetween('required_by',[$from_date,$to_date]);
        else if(empty($vendor))
            $workOrder = WorkOrders::where('status',$status)->whereBetween('required_by',[$from_date,$to_date]);
        else if(empty($status))
            $workOrder = WorkOrders::where('vendor_id',$vendor)->whereBetween('required_by',[$from_date,$to_date]);
        else
            $workOrder = WorkOrders::where(['vendor_id'=>$vendor,'status'=>$status])->whereBetween('required_by',[$from_date,$to_date]);

        
        // dd($ids);
        $index['workOrder'] = $workOrder->get();
        $index['is_vendor'] = !empty($vendor) ? true : false;
        $index['vendorName'] = Vendor::where('id',$vendor)->exists() ? Vendor::where('id',$vendor)->first()->name : "";
        $index['gtotal'] = $workOrder->sum('price');
        $index['date'] = collect(['from_date'=>$from_date,'to_date'=>$to_date]);
        $index['result'] = "";
        // dd($index);
        return view('work_orders.report-print',$index);
    }

    public function index()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $vehicle_ids = VehicleModel::pluck('id')->toArray();
        } else {
            $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
        }
        $index['data'] = WorkOrders::whereIn('vehicle_id', $vehicle_ids)->orderBy('id', 'desc')->get();
        foreach($index['data'] as $d){
            $trash = Transaction::where(['from_id'=>$d->id,'param_id'=>28]);
            $d->is_transaction = $trash->exists() ? true :false;
        }
        return view('work_orders.index', $index);
    }

    public function create()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
        }

        $data['vendors'] = Vendor::whereNotIn('type',['fuel','Fuel'])->get();
        $data['parts'] = PartsModel::where('stock', '>', 0)->where('availability', 1)->get();
        $data['is_gst'] = [1=>'Yes',2=>'No'];
        return view('work_orders.create', $data);
    }

    public function store(WorkOrderRequest $request)
    {
        // dd($request->all());
        $order = new WorkOrders();
        // GST Calculations
		$total = empty($request->price) ? null : (float) $request->price;
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
		$order->is_gst = $isgst;
		$order->cgst = $cgst;
		$order->sgst = $sgst;
		$order->cgst_amt = Helper::properDecimals($cgstval);
		$order->sgst_amt = Helper::properDecimals($sgstval);
        $order->price = Helper::properDecimals($total);
		$order->grand_total = Helper::properDecimals($grandtotal);
        $order->required_by = $request->get('required_by');
        $order->vehicle_id = $request->get('vehicle_id');
        $order->vendor_id = $request->get('vendor_id');
        $order->status = $request->get('status');
        $order->description = $request->get('description');
        $order->meter = $request->get('meter');
        $order->note = $request->get('note');
        $order->save();
        $order_id = $order->id;
        $log = WorkOrderLogs::create([
            'created_on' => date('Y-m-d', strtotime($order->created_at)),
            'work_id' => $order->id,
            'vehicle_id' => $order->vehicle_id,
            'vendor_id' => $order->vendor_id,
            'required_by' => $order->required_by,
            'status' => $order->status,
            'description' => $order->description,
            'meter' => $order->meter,
            'note' => $order->note,
            'price' => $grandtotal,
            'type' => "Created",
        ]);
        $parts = $request->parts;

        if ($parts != null) {
            foreach ($parts as $part_id => $qty) {

                $update_part = PartsModel::find($part_id);
                PartsUsedModel::create(['work_id' => $order->id, 'part_id' => $part_id, 'qty' => $qty, 'price' => $update_part->unit_cost, 'total' => $qty * $update_part->unit_cost]);
                $update_part->stock = $update_part->stock - $qty;
                $update_part->save();
                if ($update_part->stock == 0) {
                    $update_part->availability = 0;
                    $update_part->save();
                }
            }
        }
        $log->parts_price = $order->parts->sum('total');
        $log->save();

        if(!empty($order_id)){
            $account['from_id'] = $order_id; //Work Order id
            $account['type'] = 24; //Credit 
            $account['param_id'] = 28; //From Work Order
            $account['advance_for'] = null; //No Advance needed
            $account['total'] = $grandtotal;
            

            $transid = Transaction::create($account)->id;
            $trash = ['type'=>24,'from'=>28,'id'=>$transid];
            $transaction_id = Helper::transaction_id($trash);
            Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);
            
            $income['transaction_id'] = $transid;
            $income['payment_method'] = 16;
            $income['date'] = date("Y-m-d H:i:s");
            $income['amount'] = 0;
            $income['remaining'] = $grandtotal;
            $income['remarks'] = null;

            IncomeExpense::create($income);
        }
        return redirect()->route('work_order.index');
    }

    public function edit($id)
    {
        // dd($id);
        $index['parts'] = PartsModel::where('stock', '>', 0)->where('availability', 1)->get();
        $index['data'] = WorkOrders::whereId($id)->first();
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $index['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $index['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
        }
        $index['vendors'] = Vendor::get();
        $index['is_gst'] = [1=>'Yes',2=>'No'];
        // dd($index);
        return view('work_orders.edit', $index);
    }

    public function update(WorkOrderRequest $request)
    {
        // dd($request->all());
        $order = WorkOrders::find($request->get("id"));

        // GST Calculations
		$total = empty($request->price) ? null : (float) $request->price;
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
		$order->is_gst = $isgst;
		$order->cgst = $cgst;
		$order->sgst = $sgst;
		$order->cgst_amt = Helper::properDecimals($cgstval);
		$order->sgst_amt = Helper::properDecimals($sgstval);
        $order->price = Helper::properDecimals($total);
		$order->grand_total = Helper::properDecimals($grandtotal);
        $order->required_by = $request->get('required_by');
        $order->vehicle_id = $request->get('vehicle_id');
        $order->vendor_id = $request->get('vendor_id');
        $order->status = $request->get('status');
        $order->description = $request->get('description');
        $order->meter = $request->get('meter');
        $order->note = $request->get('note');
        $order->save();

        $log = WorkOrderLogs::create([
            'created_on' => date('Y-m-d', strtotime($order->created_at)),
            'work_id' => $order->id,
            'vehicle_id' => $order->vehicle_id,
            'vendor_id' => $order->vendor_id,
            'required_by' => $order->required_by,
            'status' => $order->status,
            'description' => $order->description,
            'meter' => $order->meter,
            'note' => $order->note,
            'price' => Helper::properDecimals($grandtotal),
            'type' => "Updated",
        ]);
        $parts = $request->parts;

        if ($parts != null) {
            foreach ($parts as $part_id => $qty) {
                $update_part = PartsModel::find($part_id);

                PartsUsedModel::create(['work_id' => $order->id, 'part_id' => $part_id, 'qty' => $qty, 'price' => $update_part->unit_cost, 'total' => $qty * $update_part->unit_cost]);
                $update_part->stock = $update_part->stock - $qty;
                $update_part->save();
                if ($update_part->stock == 0) {
                    $update_part->availability = 0;
                    $update_part->save();
                }
            }
        }
        $log->parts_price = $order->parts->sum('total');
        $log->save();

        $trash = Transaction::where(['from_id'=>$order->id,'param_id'=>28]);
        $tran_id = $trash->first()->id;
        $trash->update(['total'=>Helper::properDecimals($grandtotal)]);
        IncomeExpense::where('transaction_id',$tran_id)->update(['remaining'=>Helper::properDecimals($grandtotal)]);
        return redirect()->route('work_order.index');
    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $trns = Transaction::where(['from_id'=>$id,'param_id'=>28]);
        $trns_id = $trns->first()->id;
        $trns->delete();
        // dd($trns_id);
        IncomeExpense::where('transaction_id',$trns_id)->delete();
        WorkOrders::find($id)->delete();
        return redirect()->back();
    }

    public function bulk_delete(Request $request)
    {
        WorkOrders::whereIn('id', $request->ids)->delete();
        return back();
    }

    public function remove_part($id)
    {
        $usedpart = PartsUsedModel::find($id);
        $part = PartsModel::find($usedpart->part_id);
        $part->stock = $part->stock + $usedpart->qty;
        $part->save();
        if ($part->stock > 0) {
            $part->availability = 1;
            $part->save();
        }
        $usedpart->delete();
        return back();
    }

    public function parts_used($id)
    {
        $order = WorkOrders::find($id);
        return view('work_orders.parts_used', compact('order'));
    }

    public function wo_gstcalculate(Request $request){
        // return response()->json($request->all());
        $price = empty($request->price) ? null : (float) $request->price;
		$cgst = empty($request->cgst) ? null : (float) $request->cgst;
		$sgst = empty($request->sgst) ? null : (float) $request->sgst;

		$total = $price;

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

		$grandtotal = $total + $cgstval + $sgstval;
		$response['total'] = Helper::properDecimals($total);
		$response['cgstval'] = Helper::properDecimals($cgstval);
		$response['sgstval'] = Helper::properDecimals($sgstval);
		$response['grandtotal'] = Helper::properDecimals($grandtotal);
		return response()->json($response);
    }

    public function view_event($id){
        $data['row'] = WorkOrders::find($id);
        // dd($data);
        return view('work_orders.view_event',$data);
    }
}
