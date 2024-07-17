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
        // dd($request->availability);
        foreach ($request->item as $k => $v) {
            // print($request->number[$k]);
            $category_id = PartsModel::find($v)->category_id;
            $eachArr = [
                'parts_id' => $v,
                'partsinv_id' => $id,
                'parts_category' => $category_id,
                'unit_cost' => $request->unit_cost[$k],
                'quantity' => $request->stock[$k],
                'total' => $request->total[$k],
                'date_of_purchase' => $dateofpurchase
            ];
            if (isset($request->tyre_number[$k]) && $request->tyre_number[$k] !== '') {
                $tyreNumbers = explode(',', $request->tyre_number[$k]);
                $tyreNumbers = array_map('trim', $tyreNumbers);
                $tyreNumbers = array_filter($tyreNumbers);
    
                if (count($tyreNumbers) != $request->stock[$k]) {
                    return redirect()->back()->withInput()->withErrors(['tyre_number' => 'The number of tyre numbers must match the quantity for each item.']);
                }
    
                $eachArr['tyre_numbers'] = implode(',', $tyreNumbers);
                $allTyreNumbers = array_merge($allTyreNumbers, $tyreNumbers); // Collect all tyre numbers
            }
    
            $createdPart = PartsDetails::create($eachArr);
            if (!empty($allTyreNumbers)) {
                $parts->tyre_numbers = implode(',', array_unique($allTyreNumbers));
            }
        
            $parts->save();

            $data = [
                'part_id' => $v,
                'qty' => $request->stock[$k],
                'param_id' => 46,
                'from_id' => $id,
            ];
            $old_stock = PartsModel::find($v)->stock;
            $newstock = $old_stock + $request->stock[$k];
            $updated = PartsModel::where('id', $v)->update(['stock' => $newstock]);
            if ($updated) Stock::create($data);
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
        //    dd('delete');
        //    dd($request->all());
        // $form_id = DB::table('transactions')
        //      ->select('id')
        //      ->where('from_id','=', $request->get('id'))
        //      ->where('param_id','=', 26)
        //      ->where('type','=', 24)
        //      ->firstOrFail()->id;
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
            $old_stock = PartsModel::find($dt->parts_id)->stock;
            $newstock = $old_stock - $dt->quantity;
            PartsModel::where('id', $dt->parts_id)->update(['stock' => $newstock]);
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
        $index['vendors'] = Vendor::pluck('name', 'id');
        $index['categories'] = PartsCategoryModel::pluck('name', 'id');
        $index['is_gst'] = [1 => 'Yes', 2 => 'No'];
        $index['items'] = Helper::getAllParts();
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
        // dd($partsInvoice->partsDetails);
        // dd($request->all());
        $part_id = $request->id;
        // $partinv_id = $partsInvoice->id;
        $billno = $request->billno;
        $vendor_id = $request->vendor_id;
        $cash_payment = $request->cash_payment;
        $cheque_draft = $request->cheque_draft;
        $cheque_draft_amount = $request->cheque_draft_amount;
        $cheque_draft_date = $request->cheque_draft_date;
        $dateofpurchase = date("Y-m-d", strtotime($request->dateofpurchase));
        // //delete previous part_details containing $part_id
        // PartsDetails::where('partsinv_id',$partinv_id)->delete();
        //resetting stocks in items table
        foreach ($partsInvoice->partsDetails as $pd) {
            $currentStock = PartsModel::find($pd->parts_id)->stock;
            $newstock = $currentStock - $pd->quantity;
            PartsModel::where('id', $pd->parts_id)->update(['stock' => $newstock]);
            PartsDetails::find($pd->id)->delete();
        }

        //Insert new data
        foreach ($request->item as $key => $val) {
            //update stock
            $category_id = PartsModel::find($val)->category_id;
            $currentStock = PartsModel::find($val)->stock;
            $newstock = $currentStock + $request->stock[$key];
            $details_data = [
                'parts_id' => $val,
                'partsinv_id' => $part_id,
                'parts_category' => $category_id,
                // 'number' => $request->number[$key],
                // 'manufacture' => $request->manufacturer[$key],
                // 'status' => $request->status[$key],
                'unit_cost' => $request->unit_cost[$key],
                // 'availability' => $request->availability[$key],
                'quantity' => $request->stock[$key],
                'date_of_purchase' => $dateofpurchase,
                'total' => bcdiv($request->total[$key], 1, 2),
            ];
            if (isset($request->tyre_number[$key]) && $request->tyre_number[$key] !== '') {
                $tyreNumbers = explode(',', $request->tyre_number[$key]);
                $tyreNumbers = array_map('trim', $tyreNumbers);
                $tyreNumbers = array_filter($tyreNumbers);
    
                if (count($tyreNumbers) != $request->stock[$key]) {
                    return redirect()->back()->withInput()->withErrors(['tyre_number' => 'The number of tyre numbers must match the quantity for each item.']);
                }
    
                $details_data['tyre_numbers'] = implode(',', $tyreNumbers);
                $allTyreNumbers = array_merge($allTyreNumbers, $tyreNumbers);
            }
    
            if (PartsDetails::create($details_data))
                PartsModel::where('id', $val)->update(['stock' => $newstock]);
        }
    
        // Update the main PartsInvoice with all tyre numbers
        if (!empty($allTyreNumbers)) {
            $partsInvoice->tyre_numbers = implode(',', array_unique($allTyreNumbers));
            $partsInvoice->save();
        }

        //     if (PartsDetails::create($details_data))
        //         PartsModel::where('id', $val)->update(['stock' => $newstock]);
        // }


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
