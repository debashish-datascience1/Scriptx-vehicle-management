<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrderRequest;
use App\Model\PartsModel;
use App\Model\PartsInvoice;
use App\Model\PartsCategoryModel;
use App\Model\Manufacturer;
use App\Model\UnitModel;
use App\Model\PartsDetails;
use App\Model\PartsUsedModel;
use App\Model\VehicleModel;
use App\Model\Vendor;
use App\Model\WorkOrderLogs;
use App\Model\WorkOrders;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\PartsNumber;
use App\Model\WorkOrderCategory;
use Helper;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

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

    public function index()
    {
        //for clean start
        // $workOrder = WorkOrders::get();
        // foreach($workOrder as $wo){
        //     $transaction = Transaction::where(['from_id'=>$wo->id,'param_id'=>28]);
        //     if($transaction->exists()){
        //         IncomeExpense::where('transaction_id',$transaction->first()->id)->delete();
        //         $transaction->delete();
        //     }
        // }


        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $vehicle_ids = VehicleModel::pluck('id')->toArray();
        } else {
            $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
        }
        $index['data'] = WorkOrders::whereIn('vehicle_id', $vehicle_ids)->orderBy('id', 'desc')->get();
        foreach ($index['data'] as $d) {
            $trash = Transaction::where(['from_id' => $d->id, 'param_id' => 28]);
            $d->is_transaction = $trash->exists() ? true : false;
            $d->trash_id = $trash->exists() ? $trash->first()->transaction_id : null;

            //Is Item Addable
            if (!empty($d->parts)) {
                $itemCheck = array();
                foreach ($d->parts as $p) {
                    if (!empty($p->part) && !empty($p->part->category))
                        $itemCheck[] = $p->part->category->is_itemno;
                    else
                        $itemCheck[] = 0;
                }
                $d->is_itemno = array_sum($itemCheck) > 0 ? true : false;
            }
        }
        // dd($index['data']->first()->part_numbers);
        // dd($index);
        return view('work_orders.index', $index);
    }

    public function create()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
        }
        
        $availableParts = Helper::getAllParts();
        $options['add_new'] = "Add New";
        $data['vendors'] = Vendor::whereNotIn('type', ['fuel', 'Fuel'])->where('id', '!=', 7)->get();
        $data['availableParts'] = $availableParts;
        $data['orderHeads'] = WorkOrderCategory::active()->pluck('name', 'id');
        $data['parts'] = $options;
        $data['is_gst'] = [1 => 'Yes', 2 => 'No'];
        // dd($availableParts,$options);
        return view('work_orders.create', $data);
    }

    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = uniqid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $x = WorkOrders::find($id)->update([$field => $fileName1]);
    }

    public function store(WorkOrderRequest $request)
    {
        // dd($request->all());
        // dd($request->parts_id);
        $order = new WorkOrders();

        $order->price = bcdiv($request->price, 1, 2);
        $order->bill_no = $request->get('bill_no');
        $order->required_by = Helper::ymd($request->get('required_by'));
        $order->vehicle_id = $request->get('vehicle_id');
        $order->category_id = $request->get('category_id');
        $order->vendor_id = $request->get('vendor_id');
        $order->status = $request->get('status');
        $order->description = $request->get('description');
        $order->meter = $request->get('meter');
        $order->note = $request->get('note');
        $order->save();
        $order_id = $order->id;

        //Bill Image Upload
        if ($request->file('bill_image') && $request->file('bill_image')->isValid()) {
            $this->upload_file($request->file('bill_image'), "bill_image", $order_id);
        }

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
            // 'price' => $grandtotal,
            'type' => "Created",
        ]);
       
        \Log::info('Full Request Data:', $request->all());

       
        if ($request->is_addParts == 1) {
            $parts_id = $request->parts_id;
            $tyre_numbers = $request->tyre_numbers;
            $manual_tyre_numbers = $request->manual_tyre_numbers;
            $tyre_index = 0;
    
            foreach ($parts_id as $key => $part) {
                $qty = $request->qty[$key] ?? null;
                $unit_cost = $request->unit_cost[$key] ?? null;
                $total = $qty * $unit_cost;   
                $order->is_own = $request->is_own[$key]; 
                $order->quantity = $qty;
    
                // Assign tyre numbers to this part
                $part_tyre_numbers = [];
                for ($i = 0; $i < $qty; $i++) {
                    if (isset($tyre_numbers[$tyre_index])) {
                        $part_tyre_numbers[] = $tyre_numbers[$tyre_index];
                        $tyre_index++;
                    }
                }
    
                $tyre_used = implode(',', $part_tyre_numbers);
                \Log::info("Part ID: $part, Tyre Numbers: " . $tyre_used);
    
                // Get the current tyres_used from PartsModel
                $parts_model = PartsModel::find($part);
                $current_tyres = array_filter(explode(',', $parts_model->tyres_used ?? ''));
    
                // Remove the tyre numbers from PartsModel
                $remaining_tyres = array_diff($current_tyres, $part_tyre_numbers);
                $parts_model->tyres_used = implode(',', $remaining_tyres);
                $parts_model->save();
    
                if ($request->is_own[$key] == 2) { // not from own inventory
                    $non_stock_tyre_numbers = $manual_tyre_numbers[$key] ?? '';
                    $cgst_amt = ($request->cgst[$key] / 100) * $total;
                    $sgst_amt = ($request->sgst[$key] / 100) * $total;
                    $grand_total = $total + $cgst_amt + $sgst_amt;
                    $order->cgst = $request->cgst[$key];
                    $order->sgst = $request->sgst[$key];
                    $order->cgst_amt = $cgst_amt;
                    $order->sgst_amt = $sgst_amt;
                    PartsUsedModel::create([
                        'work_id' => $order->id,
                        'part_id' => $part,
                        'qty' => $qty,
                        'price' => $unit_cost,
                        'total' => $total,
                        'is_own' => $request->is_own[$key],
                        'cgst' => $request->cgst[$key],
                        'cgst_amt' => $cgst_amt,
                        'sgst' => $request->sgst[$key],
                        'sgst_amt' => $sgst_amt,
                        'grand_total' => $grand_total,
                        'tyre_used' => $tyre_used,
                        'non_stock_tyre_numbers' => $non_stock_tyre_numbers // New field
                    ]);
                } else {
                    PartsUsedModel::create([
                        'work_id' => $order->id,
                        'part_id' => $part,
                        'qty' => $qty,
                        'price' => $unit_cost,
                        'total' => $total,
                        'is_own' => $request->is_own[$key],
                        'grand_total' => $total,
                        'tyre_used' => $tyre_used
                    ]);
    
                    // owner inventory manage
                    $update_stock = PartsModel::find($part);
                    $update_stock->stock = $update_stock->stock - $qty;
                    $update_stock->save();
                }
            }
        }

        $partsGrandTotal = !empty($order->parts_fromvendor) ? $order->parts_fromvendor->sum('grand_total') : 0;
        $log->price = $order->price + $partsGrandTotal;
        $log->parts_price = $partsGrandTotal;
        $log->save();

        //update work order grand total (work order price + gst + parts' price)
        $order->parts_price = $partsGrandTotal;
        $order->grand_total = $order->price + $partsGrandTotal;
        $order->save();

        if (!empty($order_id)) {
            $account['from_id'] = $order_id; //Work Order id
            $account['type'] = 24; //Credit 
            $account['param_id'] = 28; //From Work Order
            $account['advance_for'] = null; //No Advance needed
            $account['total'] = $order->price + $partsGrandTotal;


            $transid = Transaction::create($account)->id;
            $trash = ['type' => 24, 'from' => 28, 'id' => $transid];
            $transaction_id = Helper::transaction_id($trash);
            Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

            $income['transaction_id'] = $transid;
            $income['payment_method'] = 16;
            $income['date'] = date("Y-m-d H:i:s");
            $income['amount'] = 0;
            $income['remaining'] = $order->price + $partsGrandTotal;
            $income['remarks'] = null;

            IncomeExpense::create($income);
        }
        return redirect()->route('work_order.index');
    }

    public function edit($id)
    {
        // dd($id);
        $index['parts'] = PartsModel::where('stock', '>', 0)->get();
        $index['data'] = WorkOrders::whereId($id)->first();
        foreach ($index['data']->parts as $part) {
            if ($part->is_own == 2) { // not from own stock
                $part->non_stock_tyre_numbers = $part->non_stock_tyre_numbers;
            }
        }
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $index['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $index['vehicles'] = VehicleModel::where('group_id', Auth::user()->group_id)->whereIn_service("1")->get();
        }
        $options = Helper::getAllParts();
        $options['add_new'] = "Add New";
        $index['vendors'] = Vendor::get();
        $index['orderHeads'] = WorkOrderCategory::active()->pluck('name', 'id');
        $index['is_gst'] = [1 => 'Yes', 2 => 'No'];
        $index['options'] = $options;
        $index['partsWithStock'] = PartsModel::pluck('stock', 'id')->toJson();
        

        // dd($index);
        return view('work_orders.edit', $index);
    }

    public function storeOrderHead(WorkOrders $workOrder)
    {
        // dd($workOrder);
        $workOrderCategory = WorkOrderCategory::active()->pluck('name', 'id');
        return view('work_orders.order_head', compact('workOrder', 'workOrderCategory'));
    }

    public function storeOrderHeadPost(Request $request, WorkOrders $workOrder)
    {
        $workOrder->category_id = $request->category_id;
        $workOrder->save();
        return redirect()->route('work_order.index');
    }



    
    public function update(WorkOrderRequest $request)
    {
        $order = WorkOrders::find($request->get("id"));
        $order_id = $order->id;

        // Remove Previously Added Parts
        if (!empty($order->parts)) {
            foreach ($order->parts as $part_used) {
                $partsModel = PartsModel::find($part_used->part_id);
                if ($part_used->is_own == 1) {
                    $old_stock = empty($partsModel->stock) ? 0 : $partsModel->stock;
                    $new_stock = $old_stock + $part_used->qty;
                    $partsModel->update(['stock' => $new_stock]);
                }
                PartsUsedModel::find($part_used->id)->delete();
            }
        }

        $order->price = bcdiv($request->price, 1, 2);
        $order->bill_no = $request->get('bill_no');
        $order->required_by = Helper::ymd($request->get('required_by'));
        $order->vehicle_id = $request->get('vehicle_id');
        $order->vendor_id = $request->get('vendor_id');
        $order->status = $request->get('status');
        $order->description = $request->get('description');
        $order->meter = $request->get('meter');
        $order->note = $request->get('note');
        $order->save();

        // Bill Image Upload
        if ($request->file('bill_image') && $request->file('bill_image')->isValid()) {
            $this->upload_file($request->file('bill_image'), "bill_image", $order_id);
        }

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
            'type' => "Updated",
        ]);

        if ($request->is_addParts == 1) {
            $parts_id = $request->parts_id;
            $tyre_numbers = $request->tyre_numbers;
            $manual_tyre_numbers = $request->manual_tyre_numbers;
            $tyre_index = 0;

            foreach ($parts_id as $key => $part) {
                $qty = $request->qty[$key] ?? null;
                $unit_cost = $request->unit_cost[$key] ?? null;
                $total = $qty * $unit_cost;   
                $order->is_own = $request->is_own[$key]; 
                $order->quantity = $qty;

                // Assign tyre numbers to this part
                $part_tyre_numbers = [];
                for ($i = 0; $i < $qty; $i++) {
                    if (isset($tyre_numbers[$tyre_index])) {
                        $part_tyre_numbers[] = $tyre_numbers[$tyre_index];
                        $tyre_index++;
                    }
                }

                $tyre_used = implode(',', $part_tyre_numbers);

                if ($request->is_own[$key] == 2) { // not from own inventory
                    $non_stock_tyre_numbers = $manual_tyre_numbers[$key] ?? '';
                    PartsUsedModel::create([
                        'work_id' => $order->id,
                        'part_id' => $part,
                        'qty' => $qty,
                        'price' => $unit_cost,
                        'total' => $total,
                        'is_own' => $request->is_own[$key],
                        'cgst' => $request->cgst[$key] ?? null,
                        'cgst_amt' => ($request->cgst[$key] / 100) * $total ?? null,
                        'sgst' => $request->sgst[$key] ?? null,
                        'sgst_amt' => ($request->sgst[$key] / 100) * $total ?? null,
                        'grand_total' => $total + (($request->cgst[$key] / 100) * $total ?? 0) + (($request->sgst[$key] / 100) * $total ?? 0),
                        'tyre_used' => $tyre_used,
                        'non_stock_tyre_numbers' => $non_stock_tyre_numbers
                    ]);
                } else {
                    PartsUsedModel::create([
                        'work_id' => $order->id,
                        'part_id' => $part,
                        'qty' => $qty,
                        'price' => $unit_cost,
                        'total' => $total,
                        'is_own' => $request->is_own[$key],
                        'cgst' => $request->cgst[$key] ?? null,
                        'cgst_amt' => ($request->cgst[$key] / 100) * $total ?? null,
                        'sgst' => $request->sgst[$key] ?? null,
                        'sgst_amt' => ($request->sgst[$key] / 100) * $total ?? null,
                        'grand_total' => $total + (($request->cgst[$key] / 100) * $total ?? 0) + (($request->sgst[$key] / 100) * $total ?? 0),
                        'tyre_used' => $tyre_used
                    ]);
                }

                // Get the previously used tyres for this part and work order
                $previous_used = PartsUsedModel::where('part_id', $part)
                    ->where('work_id', $order->id)
                    ->whereNotNull('deleted_at')
                    ->latest('deleted_at')
                    ->withTrashed()
                    ->value('tyre_used');

                $previous_tyres = $previous_used ? explode(',', $previous_used) : [];
                print_r($part_tyre_numbers,'\n');
                // Get the current tyres_used from PartsModel
                $parts_model = PartsModel::find($part);
                $current_tyres = array_filter(explode(',', $parts_model->tyres_used ?? ''));
                print_r($current_tyres,'\n');

                // Find the intersection
                $intersection = array_intersect($previous_tyres, $part_tyre_numbers);
                print_r($intersection,'\n');


                if (empty($intersection)) {
                    // If there's no intersection, add all previous tyres to PartsModel
                    $tyres_to_add = $previous_tyres;
                    print_r($tyres_to_add,'\n');
                } else {
                    // If there's an intersection, add only the tyres that are not in the new selection
                    $tyres_to_add = array_diff($previous_tyres, $part_tyre_numbers);
                    print_r($tyres_to_add,'\n');

                }

                // Add the tyres to PartsModel
                $updated_tyres = array_unique(array_merge($current_tyres, $tyres_to_add));
                print_r($updated_tyres,'\n');

                // dd($updated_tyres);
                $final_tyres = array_diff($updated_tyres,$part_tyre_numbers);
                // dd($final_tyres);
                $parts_model->tyres_used = implode(',', $final_tyres);
                $parts_model->save();

                // Update stock if it's from own inventory
                if ($request->is_own[$key] == 1) {
                    $update_stock = PartsModel::find($part);
                    $update_stock->stock = $update_stock->stock - $qty;
                    $update_stock->save();
                }
            }
        }

        $partsGrandTotal = !empty($order->parts_fromvendor) ? $order->parts_fromvendor->sum('grand_total') : 0;
        $grandtotal = $order->price + $partsGrandTotal;
        $log->price = $grandtotal;
        $log->parts_price = $partsGrandTotal;
        $log->save();

        // update work order grand total (work order price + gst + parts' price)
        $order->parts_price = $partsGrandTotal;
        $order->grand_total = $grandtotal;
        $order->save();

        $trash = Transaction::where(['from_id' => $order->id, 'param_id' => 28]);
        $tran_id = $trash->first()->id;
        $trash->update(['total' => bcdiv($grandtotal, 1, 2)]);
        // no remaining in work orders
        IncomeExpense::where('transaction_id', $tran_id)->update(['remaining' => bcdiv($grandtotal, 1, 2)]);
        return redirect()->back();
    }

    
    public function destroy(Request $request)
    {
        $id = $request->id;
        $trns = Transaction::where(['from_id' => $id, 'param_id' => 28]);
        $trns_id = $trns->first()->id;
        $trns->delete();
        IncomeExpense::where('transaction_id', $trns_id)->delete();
        
        $workOrder = WorkOrders::find($id);
        foreach ($workOrder->parts as $part_used) {
            $partsModel = PartsModel::find($part_used->part_id);
            
            // Restore tyre numbers
            if (!empty($part_used->tyre_used)) {
                $tyre_numbers = explode(',', $part_used->tyre_used);
                $current_tyres = array_filter(explode(',', $partsModel->tyres_used));
                $restored_tyres = array_unique(array_merge($current_tyres, $tyre_numbers));
                $partsModel->tyres_used = implode(',', $restored_tyres);
            }
            
            if ($part_used->is_own == 1) {
                $old_stock = $partsModel->stock;
                $new_stock = $old_stock + $part_used->qty;
                $partsModel->stock = $new_stock;
            }
            
            $partsModel->save();
            PartsUsedModel::find($part_used->id)->delete();
        }
        
        $workOrder->delete();
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

    public function wo_gstcalculate(Request $request)
    {
        // return response()->json($request->all());
        //getting total from the  quantity and price
        $calcArray = $request->calcArray;
        $cprcArray = $request->cprcArray;
        $price = empty($request->price) ? null : (float) $request->price;
        if (!empty($calcArray)) {
            foreach ($calcArray as $k => $v) {
                $priceAll[] = $cprcArray[$k] * $v;
            }
        }
        $price_parts = isset($priceAll) ? array_sum($priceAll) : 0;
        $total = $price + $price_parts;
        $cgst = empty($request->cgst) ? null : (float) $request->cgst;
        $sgst = empty($request->sgst) ? null : (float) $request->sgst;

        // $total = $price;

        // CGST Calculation
        if (!empty($cgst)) {
            $cgstval = ($cgst / 100) * $price_parts;
        } else {
            $cgstval = null;
        }

        // SGST Calculation
        if (!empty($sgst)) {
            $sgstval = ($sgst / 100) * $price_parts;
        } else {
            $sgstval = null;
        }

        $grandtotal = $total + $cgstval + $sgstval;
        $response['total'] = Helper::properDecimals($total);
        $response['cgstval'] = Helper::properDecimals($cgstval);
        $response['sgstval'] = Helper::properDecimals($sgstval);
        $response['grandtotal'] = Helper::properDecimals($grandtotal);
        return response()->json($response);
    }

    public function view_event($id)
    {
        $data['row'] = WorkOrders::find($id);
        // dd($data);
        return view('work_orders.view_event', $data);
    }

    // public function add_parts(Request $request)
    // {
    //     // dd($request->all());
    //     $count = $request->count;
    //     // $availableParts = !empty(Helper::getAllParts(false)) ? Helper::getAllParts(false)->where('stock','>','0') : null;
    //     // if(!empty($availableParts)){
    //     //     foreach($availableParts as $p){
    //     //         $options[$p->id] = $p->item." ".$p->catname." (".$p->manufac.")";
    //     //     }
    //     // }
    //     $options = Helper::getAllParts();
    //     $options['add_new'] = "Add New";
    //     return view("work_orders.add_parts", compact('count', 'options'));
    // }

    public function add_parts(Request $request)
{
    $count = $request->count;
    $options = Helper::getAllParts();
    $options['add_new'] = "Add New";
    
    // Fetch all parts with their stock
    $partsWithStock = DB::table('parts')->pluck('stock', 'id')->toJson();
    
    return view("work_orders.add_parts", compact('count', 'options', 'partsWithStock'));
}

    public function getTyreNumbers(Request $request)
    {
        $partId = $request->input('part_id');
        $part = PartsModel::find($partId);
        
        // \Log::info('Requested Part ID: ' . $partId);
        // \Log::info('Found Part: ' . json_encode($part));
        
        if ($part && $part->tyres_used) {
            $tyreNumbers = explode(',', $part->tyres_used);
            // \Log::info('Tyre Numbers: ' . json_encode($tyreNumbers));
            return response()->json($tyreNumbers);
        }
        
        // \Log::info('No tyre numbers found for part ID: ' . $partId);
        return response()->json([]);
    }

    public function getEditTyreNumbers(Request $request)
    {
        $partId = $request->input('part_id');
        $part = PartsModel::find($partId);
        
        // \Log::info('Requested Part ID: ' . $partId);
        // \Log::info('Found Part: ' . json_encode($part));
        
        if ($part && $part->tyre_numbers) {
            $tyreNumbers = explode(',', $part->tyre_numbers);
            // \Log::info('Tyre Numbers: ' . json_encode($tyreNumbers));
            return response()->json($tyreNumbers);
        }
        
        // \Log::info('No tyre numbers found for part ID: ' . $partId);
        return response()->json([]);
    }

    public function new_part()
    {
        $categories = PartsCategoryModel::pluck('name', 'id')->toArray();
        $manufacturers = Manufacturer::pluck('name', 'id')->toArray();
        $units = UnitModel::pluck('name', 'id')->toArray();
        $data['categories'] = $categories;
        $data['manufacturers'] = $manufacturers;
        $data['units'] = $units;
        return view('work_orders.new_part', $data);
    }

    public function new_partpost(Request $request)
    {
        // return $request;
        // dd($request->all());
        $item = $request->item;
        $category_id = $request->category_id;
        $patCat = PartsCategoryModel::find($category_id);
        $manufacturer = $request->manufacturer;
        $manufac = Manufacturer::find($manufacturer);
        $formData = $request->all();
        unset($formData['_token']);
        // dd($formData);
        $existsCheck = PartsModel::where(['item' => $item, 'category_id' => $category_id, 'manufacturer' => $manufacturer]);
        if (!$existsCheck->exists()) {
            PartsModel::create($formData);
        }

        $options = Helper::getAllParts();
        $options['add_new'] = "Add New";

        $optionsHtml = "<option value=''>Select Part</option>";
        foreach ($options as $k => $v) {
            $optionsHtml .= "<option value='$k'>$v</option>";
        }
        return $optionsHtml;
    }

    public function wo_calcgst(Request $request)
    {
        // return $request;

        $total = $request->qty * $request->unit_cost;
        //gst
        if ($request->is_own != 1) {
            $cgst = $request->cgst;
            $sgst = $request->sgst;
            if ($cgst != "")
                $cgst_amt = ($cgst / 100) * $total;
            else
                $cgst_amt = 0;
            if ($sgst != "")
                $sgst_amt = ($sgst / 100) * $total;
            else
                $sgst_amt = 0;
        } else {
            $cgst_amt = 0;
            $sgst_amt = 0;
        }

        $response['cgst_amt'] = bcdiv($cgst_amt, 1, 2);
        $response['sgst_amt'] = bcdiv($sgst_amt, 1, 2);
        $response['total'] = bcdiv($total, 1, 2);
        $response['grand_total'] = bcdiv($total + $cgst_amt + $sgst_amt, 1, 2);

        return response()->json($response);
    }

    public function othercalc(Request $request)
    {
        $billable = !is_null($request->billable) ? array_sum($request->billable) : 0.00;
        $nonbillable = !is_null($request->nonbillable) ? array_sum($request->nonbillable) : 0.00;
        $total = !is_null($request->total) ? array_sum($request->total) : 0.00;
        $price = !is_null($request->price) ? $request->price : 0.00;
        $grandtotal = $billable + $price;

        $index['billable'] = bcdiv($billable, 1, 2);
        $index['nonbillable'] = bcdiv($nonbillable, 1, 2);
        $index['grandtotal'] = bcdiv($grandtotal, 1, 2);
        return response()->json($index);
    }

    public function itemno_get(PartsUsedModel $used)
    {
        // dd($used);
        return view('work_orders.item_store', compact('used'));
    }

    public function itemno_store(Request $request)
    {
        // dd($request->all());
        $used = PartsUsedModel::find($request->used_id);
        $category_id = $used->part->category->id;

        //delete before insert
        PartsNumber::where(['order_id' => $used->work_id, 'used_id' => $used->id])->delete();
        foreach ($request->itemno as $item) {
            $data_arr = [
                'order_id' => $used->work_id,
                'part_id' => $used->part_id,
                'used_id' => $used->id,
                'category_id' => $category_id,
                'slno' => $item,
                'desc' => null,
            ];
            PartsNumber::create($data_arr);
        }

        return redirect()->back();
    }
}
