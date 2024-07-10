<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\EmiModel;
use App\Model\IncomeExpense;
use App\Model\PurchaseInfo;
use App\Model\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Model\Transaction;
use App\Helpers\Helper;


class EmiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index['emi'] = EmiModel::descending()->get();
        return view('emi.index', $index);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $index['vehicles'] = VehicleModel::whereHas('purchase_info', function ($query) {
            $query->whereRaw('emi_date IS NOT NULL AND loan_duration IS NOT NULL and duration_unit IS NOT NULL');
        })->pluck('license_plate', 'id');
        $index['request'] = null;
        // $index['purchase_infos'] = PurchaseInfo::with('vehicle')->get();
        // dd($index);
        return view('emi.create', $index);
    }


    public function search(Request $request)
    {
        // dd(request()->all());
        $index['vehicles'] = VehicleModel::whereHas('purchase_info', function ($query) {
            $query->whereRaw('emi_date IS NOT NULL AND loan_duration IS NOT NULL and duration_unit IS NOT NULL');
        })->pluck('license_plate', 'id');
        $purchinfo = PurchaseInfo::where('vehicle_id', $request->vehicle_id)->orderBy('id', 'DESC')->first();
        // dd($purchinfo);
        for ($i = 0; $i < $purchinfo->loan_duration; $i++) {
            $date = new Carbon($purchinfo->emi_date);
            $for_date = $date->addMonths($i);
            $monthSearch = date("Y-m", strtotime($date));
            // dd($monthSearch);
            $emi_data = EmiModel::where(['purchase_id' => $purchinfo->id, 'vehicle_id' => $request->vehicle_id])->where('date', 'LIKE', "%$monthSearch%");
            if ($emi_data->exists()) {
                // dd($emi_data->first());
                $emi_data =  $emi_data->first();
                $emi_data->is_paid = true;
                $emi_array[] = $emi_data;
            } else {
                $emi_array[] = Helper::toCollection([
                    'purchase_id' => $purchinfo->id,
                    'vehicle_id' => $request->vehicle_id,
                    'vehicle' => VehicleModel::find($request->vehicle_id)->license_plate,
                    'driver_id' => null,
                    'amount' => $purchinfo->emi_amount,
                    'date' => $date,
                    'pay_date' => null,
                    'bank_id' => null,
                    'reference_no' => null,
                    'remarks' => null,
                    'is_paid' => false,
                    'is_eligible' => strtotime(date("Y-m-d")) <= strtotime($date) ? false : true,
                ]);
            }
            // $emi_data;
        }
        // $abc = array_column($emi_array, 'date');
        // dd($abc);
        // dd($emi_array);
        $index['emi_array'] = $emi_array;
        $index['request'] = $request->all();
        $index['result'] = "";

        // dd($index);
        return view('emi.create', $index);
    }


    public function showPayModal(Request $request)
    {
        $purchase_id = $request->purch;
        $vehicle_id = $request->vehicle;
        $date = $request->date;
        $monthSearch = date("Y-m", strtotime($date));
        $emi_data = EmiModel::where(['purchase_id' => $purchase_id, 'vehicle_id' => $vehicle_id])->where('date', 'LIKE', "%$monthSearch%");
        // if(!$emi_data->exists()){

        // }
        $index['purchase'] = PurchaseInfo::find($purchase_id);
        $index['vehicle'] = VehicleModel::find($vehicle_id);
        $index['date'] = date("Y-m-d", strtotime($date));
        return view('emi.pay_modal', $index);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return request();
        $purchase_id = $request->purchase_id;
        $vehicle_id = $request->vehicle_id;
        $date = $request->date;
        $amount = $request->amount;
        $pay_date = $request->pay_date;
        $bank_id = $request->bank_id;
        $method = $request->method;
        $reference_no = $request->reference_no;
        $remarks = $request->remarks;

        if (!empty(VehicleModel::find($vehicle_id)->driver) && !empty(VehicleModel::find($vehicle_id)->driver->assigned_driver))
            $driver_id = VehicleModel::find($vehicle_id)->driver->assigned_driver->id;
        else $driver_id = null;

        // return $driver_id;
        $request->merge(['driver_id' => $driver_id]);
        // return $request->all();
        $monthSearch = date("Y-m", strtotime($date));
        $emi_data = EmiModel::where(['purchase_id' => $purchase_id, 'vehicle_id' => $vehicle_id])->where('date', 'LIKE', "%$monthSearch%");

        if (!$emi_data->exists()) {
            $created = EmiModel::create($request->all());

            //Putting the records in transaction
            $account['from_id'] = $created->id; //Fuel id
            $account['type'] = 24; //Debit 
            $account['param_id'] = 50; //From Vehicle EMI
            $account['bank_id'] = $bank_id; //Marking this as paid
            $account['is_completed'] = 1; //Marking this as paid
            $account['total'] = bcdiv($amount, 1, 2);


            $transid = Transaction::create($account)->id;
            $trash = ['type' => 24, 'from' => 50, 'id' => $transid];
            $transaction_id = Helper::transaction_id($trash);
            Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

            $income['transaction_id'] = $transid;
            $income['payment_method'] = $method; //Cash
            $income['date'] = $pay_date;
            $income['amount'] = bcdiv($amount, 1, 2);
            $income['remaining'] = 0;
            $income['ref_no'] = $reference_no;
            $income['remarks'] = $remarks;

            IncomeExpense::create($income);

            if ($created) {
                $response['msg'] = "EMI Paid Successfully.";
                $response['status'] = true;
                $response['date'] = date("d-m-Y", strtotime($date));
            } else {
                $response['msg'] = "Something went wrong.";
                $response['status'] = false;
            }
        } else {
            $response['msg'] = "Record already exists.";
            $response['status'] = false;
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin\EmiModel  $emiModel
     * @return \Illuminate\Http\Response
     */
    public function show(EmiModel $emiModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin\EmiModel  $emiModel
     * @return \Illuminate\Http\Response
     */
    public function edit(EmiModel $vehicle_emi)
    {
        // dd($vehicle_emi->transaction->getRefNo->payment_method);
        return view('emi.edit', ['emiModel' => $vehicle_emi]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin\EmiModel  $emiModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmiModel $vehicle_emi)
    {
        // dd($vehicle_emi);
        $data = $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'pay_date' => 'required',
            'bank_id' => 'required',
            'method' => 'required',
            'reference_no' => 'required',
            'remarks' => '',
        ]);

        $vehicle_emi->update($data);
        return view('emi.edit', ['emiModel' => $vehicle_emi]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin\EmiModel  $emiModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmiModel $vehicle_emi)
    {
        // dd($vehicle_emi);
        $vehicle_emi->transaction->getRefNo->delete();
        $vehicle_emi->transaction->delete();
        $vehicle_emi->delete();
        return redirect()->back();
    }

    public function view_event(EmiModel $emi)
    {
        // $monthLike = date("Y-m", strtotime($emi->date));
        $emis = EmiModel::where('vehicle_id', $emi->vehicle_id)->get();
        return view('emi.view_event', compact('emi', 'emis'));
    }
}
