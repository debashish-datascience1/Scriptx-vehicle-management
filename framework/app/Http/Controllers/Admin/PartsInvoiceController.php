<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartsRequest;
use App\Model\PartsCategoryModel;
use App\Model\PartsModel;
use App\Model\PartsDetails;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\PartsInvoice;
use App\Model\PartStock;
use App\Model\Vendor;
use App\Model\Stock;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartsInvoiceController extends Controller
{

    public function index()
    {

        $index['data'] = PartsInvoice::orderBy('id', 'desc')->get();

        // dd($index);
        return view("parts_invoice.index", $index);
    }


    public function create()
    {
        $data['items'] = Helper::getAllParts();
        $data['vendors'] = Vendor::pluck('name', 'id');
        $data['categories'] = PartsCategoryModel::pluck('name', 'id');
        $data['is_gst'] = [1 => 'Yes', 2 => 'No'];
        // dd($data);
        return view("parts_invoice.create", $data);
    }

    public function getCategoryInfo(Request $request)
    {
        $itemId = $request->input('item_id');
        \Log::info('Requested Part ID: ' . $itemId);
        $part = PartsModel::findOrFail($itemId);

        $category = PartsCategoryModel::findOrFail($part->category_id);

        return response()->json([
            'category_name' => $category->name
        ]);
    }

    public function store(PartsRequest $request)
    {
        // dd($request->all());
        $parts = new PartsInvoice();
        $allTyreNumbers = []; // Array to collect all tyre numbers

        // GST Calculations
        $total = empty($request->subtotal) ? null : (float) $request->subtotal;
        $cgst = empty($request->cgst) ? null : (float) $request->cgst;
        $sgst = empty($request->sgst) ? null : (float) $request->sgst;
        $isgst = !empty($request->is_gst) ? $request->is_gst : null;

        // $total = $price;
        // dd($total);
        // CGST Calculation
        if (!empty($cgst)) {
            $cgstval = ($cgst / 100) * $total;
        } else {
            $cgstval = null;
        }

        // SGST Calculation
        if (!empty($sgst)) {
            $sgstval = ($sgst / 100) * $total;
        } else {
            $sgstval = null;
        }
        // dd($cgstval);'
        $dateofpurchase = date("Y-m-d", strtotime($request->dateofpurchase));
        $chq_date = $request->get('cheque_draft_date');
        $grandtotal = $total + $cgstval + $sgstval;
        // $abc = [$cgstval,$sgstval,$total,$grandtotal];
        // dd($abc);
        $parts->is_gst = $isgst;
        $parts->cgst = $cgst;
        $parts->sgst = $sgst;
        $parts->cgst_amt = bcdiv($cgstval, 1, 2);
        $parts->sgst_amt = bcdiv($sgstval, 1, 2);
        $parts->sub_total = bcdiv($total, 1, 2);
        $parts->date_of_purchase = $dateofpurchase;
        $parts->grand_total = bcdiv($grandtotal, 1, 2);
        $parts->user_id = Auth::user()->id;
        $parts->billno = $request->get('billno');
        $parts->vendor_id = $request->get('vendor_id');
        $parts->cash_amount = $request->get('cash_payment');
        $parts->chq_draft_number = $request->get('cheque_draft');
        $parts->chq_draft_amount = $request->get('cheque_draft_amount');
        $parts->chq_draft_date = empty($chq_date) ? null : date('Y-m-d', strtotime($chq_date));
        $parts->save();
        $id = $parts->id;
        //uploading file/doc 
        if ($request->file('invoice') && $request->file('invoice')->isValid()) {
            $this->upload_file($request->file('invoice'), "invoice", $id);
        }
        
        foreach ($request->item as $k => $v) {
        
            $partsModel = PartsModel::with('category')->find($v);
            if (!$partsModel) {
                continue;
            }
        
            $category_id = $partsModel->category_id;
        
            $isTyre = $partsModel->category && stripos($partsModel->category->name, 'tyre') !== false;
            
        
            $eachArr = [
                'parts_id' => $v,
                'partsinv_id' => $id,
                'parts_category' => $category_id,
                'unit_cost' => $request->unit_cost[$k],
                'quantity' => $request->stock[$k],
                'total' => $request->total[$k],
                'date_of_purchase' => $dateofpurchase,
                'tyre_numbers' => null, // Initialize as null for all items
            ];
        
            // Process tyre numbers if it's a tyre
            if ($isTyre && isset($request->tyre_number[$v]) && $request->tyre_number[$v] !== '') {
                $tyreNumbers = explode(',', $request->tyre_number[$v]);
                $tyreNumbers = array_map('trim', $tyreNumbers);
                $tyreNumbers = array_filter($tyreNumbers);
        
        
                if (count($tyreNumbers) != $request->stock[$k]) {
                    return redirect()->back()->withInput()->withErrors(['tyre_number' => 'The number of tyre numbers must match the quantity for tyre items.']);
                }
        
                $eachArr['tyre_numbers'] = implode(',', $tyreNumbers);
        
                $existingTyreNumbers = $partsModel->tyre_numbers ? explode(',', $partsModel->tyre_numbers) : [];
                $updatedTyreNumbers = array_unique(array_merge($existingTyreNumbers, $tyreNumbers));
                $partsModel->tyre_numbers = implode(',', $updatedTyreNumbers);
                $partsModel->tyres_used = implode(',', $updatedTyreNumbers);
            } else {
                \Log::info("Tyre number for item {$v}: not set");
            }
        
            // Create PartsDetails
            try {
                $createdPart = PartsDetails::create($eachArr);
            } catch (\Exception $e) {
                \Log::error("Failed to create PartsDetails for item {$v}: " . $e->getMessage());
            }
        
            // Update stock
            $data = [
                'part_id' => $v,
                'qty' => $request->stock[$k],
                'param_id' => 46,
                'from_id' => $id,
            ];
            $old_stock = $partsModel->stock;
            $newstock = $old_stock + $request->stock[$k];
            
            try {
                $updated = $partsModel->update(['stock' => $newstock]);
                if ($updated) {
                    Stock::create($data);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to update stock or create Stock record for item {$v}: " . $e->getMessage());
            }
        }
    

        $id_trans = Transaction::create([
            'type' => 24, //debit
            'from_id' => $id,
            'param_id' => 26,
            'total' => bcdiv($grandtotal, 1, 2),
        ])->id;
        $trash = ['type' => 24, 'from' => 26, 'id' => $id_trans];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::where('id', $id_trans)->update(['transaction_id' => $transaction_id]);

        //get cash amount
        $payemnt_method = "16";
        if ($request->cash_payment != "") {
            $amount = $request->cash_payment;
        }
        //get chq/draft amount
        if ($request->cheque_draft_amount != "") {
            $amount = $request->cheque_draft_amount;
            $payemnt_method = "17";
        }
        $amount = !isset($amount) ? 0 : $amount;


        $rem_incExp = $grandtotal - $amount;
        IncomeExpense::create([
            'transaction_id' => $id_trans,
            'payment_method' => $payemnt_method,
            'date' => $dateofpurchase,
            'amount' => bcdiv($amount, 1, 2),
            'remaining' => bcdiv($rem_incExp, 1, 2),
        ]);

        return redirect()->route("parts-invoice.index");
    }
    public function destroy(Request $request)
    {
        $query = Transaction::where(['from_id' => $request->get('id'), 'param_id' => 26, 'type' => 24]);
        $from_id = empty($query->first()) ? null : $query->first()->from_id;
        // dd($from_id);
        if (!empty($from_id)) {
            IncomeExpense::where('transaction_id', $from_id)->delete();
            Transaction::where('param_id', 26)->where('type', '=', 24)->where('from_id', $request->get('id'))->delete();
        }
        //stock adjust
        $dt_list = PartsDetails::where('partsinv_id', $request->get('id'));
        foreach ($dt_list->get() as $dt) {
            // dd($dt);
            // $old_stock = PartsModel::find($dt->parts_id)->stock;
            // $newstock = $old_stock - $dt->quantity;
            // PartsModel::where('id', $dt->parts_id)->update(['stock' => $newstock]);
            $part = PartsModel::find($dt->parts_id);
            if ($part) {
                // Update stock
                $newstock = $part->stock - $dt->quantity;
                
                // Remove tyre numbers
                $currentTyreNumbers = explode(',', $part->tyre_numbers);
                $tyreNumbersToRemove = explode(',', $dt->tyre_numbers);
                
                $updatedTyreNumbers = array_diff($currentTyreNumbers, $tyreNumbersToRemove);
                
                $currentTyresUsed = explode(',', $part->tyres_used);
                $updatedTyresUsed = array_diff($currentTyresUsed, $tyreNumbersToRemove);
    
                // Update the part
                $part->update([
                    'stock' => $newstock,
                    'tyre_numbers' => implode(',', $updatedTyreNumbers),
                    'tyres_used' => implode(',', $updatedTyresUsed)
                ]);
            }
        }
        $dt_list->delete();
        PartsInvoice::find($request->get('id'))->delete();

        return redirect()->route('parts-invoice.index');
    }

    public function get_parts_form(Request $request)
    {
        // dd($request->all());
        $data['items'] = Helper::getAllParts();
        $data['vendors'] = Vendor::pluck('name', 'id');
        $data['categories'] = PartsCategoryModel::pluck('name', 'id');
        return view('parts_invoice.parts_form', $data);
    }


    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = uniqid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $x = PartsInvoice::find($id)->update([$field => $fileName1]);
    }

    public function edit($id)
    {
        // dd($id);
        $index['data'] = PartsInvoice::whereId($id)->first();
        $index['parts_details'] = PartsDetails::where('partsinv_id', $id)->get();
        \Log::info('Requested Part ID: ' . $index['parts_details']);
        $index['vendors'] = Vendor::pluck('name', 'id');
        $index['categories'] = PartsCategoryModel::pluck('name', 'id');
        $index['is_gst'] = [1 => 'Yes', 2 => 'No'];
        $index['items'] = Helper::getAllParts();
        // foreach ($index['parts_details'] as $part_detail) {
        //     $partsModel = PartsModel::find($part_detail->parts_id);
        //     $part_detail->tyre_numbers = $partsModel ? $partsModel->tyre_numbers : '';
        // }
        // dd($index);
        return view("parts_invoice.edit", $index);
    }

    public function stock($id)
    {
        $data['data'] = PartStock::wherePart_id($id)->get();
        return view("parts.stocks", $data);
    }

    public function update(Request $request, PartsInvoice $partsInvoice)
    {
        $allTyreNumbers = []; // Array to collect all tyre numbers
        $part_id = $request->id;
        $billno = $request->billno;
        $vendor_id = $request->vendor_id;
        $cash_payment = $request->cash_payment;
        $cheque_draft = $request->cheque_draft;
        $cheque_draft_amount = $request->cheque_draft_amount;
        $cheque_draft_date = $request->cheque_draft_date;
        $dateofpurchase = date("Y-m-d", strtotime($request->dateofpurchase));
        
        foreach ($partsInvoice->partsDetails as $pd) {
            $partsModel = PartsModel::find($pd->parts_id);
            $currentStock = $partsModel->stock;
            $newstock = $currentStock - $pd->quantity;
            $partsModel->update(['stock' => $newstock]);
            
            // Remove tyre numbers associated with this invoice
            $partsModel->update(['tyre_numbers' => null, 'tyres_used' => null]); // Update this line
        
            PartsDetails::find($pd->id)->delete();
        }
        foreach ($request->item as $key => $val) {
        
            $partsModel = PartsModel::with('category')->find($val);
            if (!$partsModel) {
                continue;
            }
        
            $category_id = $partsModel->category_id;
        
            $isTyre = $partsModel->category && stripos($partsModel->category->name, 'tyre') !== false;
        
            $currentStock = $partsModel->stock;
            $newstock = $currentStock + $request->stock[$key];
        
            $details_data = [
                'parts_id' => $val,
                'partsinv_id' => $part_id,
                'parts_category' => $category_id,
                'unit_cost' => $request->unit_cost[$key],
                'quantity' => $request->stock[$key],
                'date_of_purchase' => $dateofpurchase,
                'total' => bcdiv($request->total[$key], 1, 2),
                'tyre_numbers' => null // Initialize as null for all items
            ];
        
            // Process tyre numbers if it's a tyre
            if ($isTyre && isset($request->tyre_number[$val]) && $request->tyre_number[$val] !== '') {
                $tyreNumbers = explode(',', $request->tyre_number[$val]);
                $tyreNumbers = array_map('trim', $tyreNumbers);
                $tyreNumbers = array_filter($tyreNumbers);

                $tyreMumbers = PartsDetails::where('parts_id', $val)
                ->where('parts_category', $partsModel->category_id)
                ->whereNull('deleted_at')
                ->pluck('tyre_numbers')
                ->filter()
                ->flatMap(function ($numbers) {
                    return explode(',', $numbers);
                })
                ->unique()
                ->values()
                ->toArray();

                $allTyreNumbers = array_unique(array_merge($tyreNumbers, $tyreMumbers));
                $allTyreNumbers = array_filter($allTyreNumbers, 'strlen');
                $uniqueTyreNumbers = implode(',', $allTyreNumbers);

        
                if (count($tyreNumbers) != $request->stock[$key]) {
                    return redirect()->back()->withInput()->withErrors(['tyre_number' => 'The number of tyre numbers must match the quantity for tyre items.']);
                }
        
                $details_data['tyre_numbers'] = implode(',', $tyreNumbers);
                $partsModel->tyre_numbers = $uniqueTyreNumbers;
                $partsModel->tyres_used = $uniqueTyreNumbers;
            } else {
                $partsModel->tyre_numbers = null;
                $partsModel->tyres_used = null;
            }
        
            // Create PartsDetails
            try {
                $createdPart = PartsDetails::create($details_data);
        
                // Update stock
                $partsModel->stock = $newstock;
                $updated = $partsModel->save();
        
                if ($updated) {
                    $stockData = [
                        'part_id' => $val,
                        'qty' => $request->stock[$key],
                        'param_id' => 46,
                        'from_id' => $part_id,
                    ];
                    Stock::create($stockData);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to create PartsDetails or update stock for item {$val}: " . $e->getMessage());
            }
        }

        // GST Calculations
        $total = empty($request->subtotal) ? null : (float) $request->subtotal;
        $cgst = empty($request->cgst) ? null : (float) $request->cgst;
        $sgst = empty($request->sgst) ? null : (float) $request->sgst;
        $isgst = !empty($request->is_gst) ? $request->is_gst : null;

        // $total = $price;
        // dd($total);
        // CGST Calculation
        if (!empty($cgst)) {
            $cgstval = ($cgst / 100) * $total;
        } else {
            $cgstval = null;
        }

        // SGST Calculation
        if (!empty($sgst)) {
            $sgstval = ($sgst / 100) * $total;
        } else {
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
            'cgst_amt' => bcdiv($cgstval, 1, 2),
            'sgst_amt' => bcdiv($sgstval, 1, 2),
            'sub_total' => bcdiv($total, 1, 2),
            'grand_total' => bcdiv($grandtotal, 1, 2),
            'user_id' => Auth::user()->id,
            'billno' => $billno,
            'vendor_id' => $vendor_id,
            'cash_amount' => $cash_payment,
            'chq_draft_number' => $cheque_draft,
            'chq_draft_amount' => $cheque_draft_amount,
            'chq_draft_date' => empty($cheque_draft_date) ? null : date('Y-m-d', strtotime($cheque_draft_date)),
            'date_of_purchase' => $dateofpurchase, // Add this line

        ];
        PartsInvoice::where('id', $part_id)->update($partmod);

        //Update Transaction,IncomeExpense
        //Program from here

        if (!empty($grandtotal) && !empty($total)) {
            $transa = Transaction::where(['from_id' => $part_id, 'param_id' => 26]);
            // dd($transa->get());
            $cash_p = empty($cash_payment) ? 0 : bcdiv($cash_payment, 1, 2);
            $transa->update(['total' => bcdiv($grandtotal, 1, 2)]);
            $trns_id = $transa->first()->id;
            // dd($trns_id);

            $rem = bcdiv($grandtotal - $cash_p, 1, 2);
            IncomeExpense::where('transaction_id', $trns_id)->update(['amount' => $cash_p, 'remaining' => $rem]);
        }

        return redirect()->back();
    }


    public function bulk_delete(Request $request)
    {

        $from_id = Transaction::select('id')->where('param_id', 26)->where('type', 24)->whereIn('from_id', $request->get('ids'))->get();

        IncomeExpense::whereIn('transaction_id', $from_id)->delete();
        Transaction::where('param_id', 26)->where('type', 24)->whereIn('from_id', $request->get('ids'))->delete();
        PartsDetails::whereIn('parts_id', $request->get('ids'))->delete();
        PartsModel::whereIn('id', $request->get('ids'))->delete();
        return back();
    }

    public function add_stock(Request $request)
    {
        $part = PartsModel::find($request->part_id);
        $part->stock = $part->stock + $request->stock;
        $part->save();
        return back();
    }

    public function pi_gstcalculate(Request $request)
    {
        // return response()->json($request->all());
        //getting total from the  quantity and price

        $total = $request->price;
        $cgst = empty($request->cgst) ? null : (float) $request->cgst;
        $sgst = empty($request->sgst) ? null : (float) $request->sgst;

        // $total = $price;

        // CGST Calculation
        if (!empty($cgst)) {
            $cgstval = ($cgst / 100) * $total;
        } else {
            $cgstval = null;
        }

        // SGST Calculation
        if (!empty($sgst)) {
            $sgstval = ($sgst / 100) * $total;
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
        $index['parts'] = PartsDetails::where("partsinv_id", $id)->get();
        $index['row'] = PartsInvoice::where("id", $id)->first();
        return view('parts_invoice.view_event', $index);
    }
}
