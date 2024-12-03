<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\VehicleModel;
use App\Model\VehicleDocs;
use App\Helpers\Helper;
use App\Model\BankAccount;
use App\Model\Params;
use App\Model\Vendor;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleDocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index['docs'] = VehicleDocs::orderBy('id', 'DESC')->get();
        return view('vehicle_docs.index', $index);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehiArray = array();
        $vehicles =  VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('in_service', 1)->get();
        foreach ($vehicles as $v) {
            $insu_expdate = $v->getMeta('ins_exp_date');
            $insu_dur = $v->getMeta('ins_renew_duration');
            // $insu_amt = $v->getMeta('ins_renew_amount');
            $fitness_expdate = $v->getMeta('fitness_expdate');
            $fitness_dur = $v->getMeta('fitness_renew_duration');
            // $fitness_amt = $v->getMeta('fitness_renew_amount');
            $roadtax_expdate = $v->getMeta('road_expdate');
            $roadtax_dur = $v->getMeta('roadtax_renew_duration');
            // $roadtax_amt = $v->getMeta('roadtax_renew_amount');
            $permit_expdate = $v->getMeta('permit_expdate');
            $permit_dur = $v->getMeta('permit_renew_duration');
            // $permit_amt = $v->getMeta('permit_renew_amount');
            $pollution_expdate = $v->getMeta('pollution_expdate');
            $pollution_dur = $v->getMeta('pollution_renew_duration');
            // $pollution_amt = $v->getMeta('pollution_renew_amount');
            if (((!empty($insu_dur)  && !empty($insu_expdate)) || (!empty($fitness_dur)  && !empty($fitness_expdate)) || (!empty($roadtax_dur)  && !empty($roadtax_expdate)) || (!empty($permit_dur)   && !empty($permit_expdate)) || (!empty($pollution_dur)  && !empty($pollution_expdate))) && Helper::checkEligibleRenewalVehicle($v->id)->status) {
                // dd($v);
                $v->is_renewable = 1;
                $vehiArray[$v->id] = $v->name;
                // dd($v);
            } else {
                $v->is_renewable = null;
            }
            // !is_null($v->is_renewable) ?? dd($v) ;
        }
        // dd($vehicles->toArray());
        // dd($vehiArray);
        $data['vehicles'] = $vehiArray;
        $data['method'] = Params::where('code', "PaymentMethod")->where('id', '!=', '16')->pluck('label', 'id');
        // dd($data);
        return view('vehicle_docs.create', $data);
    }

    public function renewVehicles(Request $request)
    {
        // dd($request->id);
        // if($request->id=='null') return false;
        $ids = explode(",", $request->id);
        // dd($ids);
        $data['vehicles'] = VehicleModel::whereIn('id', $ids)->get();
        $data['vendors'] = Vendor::where('type', 'Document')->pluck('name', 'id');
        $data['bankAccount'] = BankAccount::select("id", DB::raw("CONCAT(bank,'(',account_no,')') as bank"))->pluck('bank', 'id');
        $data['docparams'] = Params::where('code', 'RenewDocuments')->get();
        $data['docparamArray'] = [
            36 => ['ins_renew_duration', 'insurance_duration_unit', 'ins_exp_date'],
            37 => ['fitness_renew_duration', 'fitness_duration_unit', 'fitness_expdate'],
            38 => ['roadtax_renew_duration', 'roadtax_duration_unit', 'road_expdate'],
            39 => ['permit_renew_duration', 'permit_duration_unit', 'permit_expdate'],
            40 => ['pollution_renew_duration', 'pollution_duration_unit', 'pollution_expdate']
        ];
        $data['method'] = Params::where('code', "PaymentMethod")->pluck('label', 'id');
        // dd($data);
        return view('vehicle_docs.renew_list', $data);
    }

    // public function singleStore(Request $request)
    // {
    //     // return $request;
    //     $date = !empty($request->get('date')) ? date("Y-m-d", strtotime($request->get('date'))) : null;
    //     $amount = $request->get('amount');
    //     $vendor_id = $request->get('vendor');
    //     $bank = $request->get('bank');
    //     $method = $request->get('method');
    //     $vehicle_id = $request->get('vehicle_id');
    //     $doc_id = $request->get('doc_id');
    //     $ddno = $request->get('ddno');
    //     $remarks = $request->get('remarks');

    //     $durationNameArray = [36 => 'ins_renew_duration', 37 => 'fitness_renew_duration', 38 => 'roadtax_renew_duration', 39 => 'permit_renew_duration', 40 => 'pollution_renew_duration'];
    //     $durationUnitArray = [36 => 'insurance_duration_unit', 37 => 'fitness_duration_unit', 38 => 'roadtax_duration_unit', 39 => 'permit_duration_unit', 40 => 'pollution_duration_unit'];

    //     $vehicleMod = VehicleModel::find($vehicle_id);
    //     // dd($vehicleMod->getMeta());
    //     $driver_id = !empty($vehicleMod->driver) && !empty($vehicleMod->driver->assigned_driver) ? $vehicleMod->driver->assigned_driver->id : null; //driver_id

    //     $durationTime = $vehicleMod->getMeta($durationNameArray[$doc_id]);
    //     $durationUnit = $vehicleMod->getMeta($durationUnitArray[$doc_id]);
    //     $till = new Carbon($date);

    //     if ($durationUnit == 'years')
    //         $till->addYears($durationTime);
    //     elseif ($durationUnit == 'months')
    //         $till->addMonths($durationTime);
    //     else
    //         $till->addDays($durationTime);

    //     //Add to Vehicle Documents
    //     $dataCreate = [
    //         'vehicle_id' => $vehicle_id,
    //         'driver_id' => $driver_id,
    //         'vendor_id' => $vendor_id,
    //         'param_id' => $doc_id, //document param id
    //         'date' => $date, //on date
    //         'till' => $till,
    //         'amount' => bcdiv($amount, 1, 2),
    //         'status' => 1,
    //         'remarks' => $remarks,
    //         'method' => $method,
    //         'ddno' => $ddno,
    //     ];
    //     $id = VehicleDocs::create($dataCreate)->id;

    //     //transaction
    //     $accountTransa['from_id'] = $id; //Vehicle Docs ID
    //     $accountTransa['type'] = 24; // Debit 
    //     $accountTransa['bank_id'] = $bank; // Bank ID
    //     $accountTransa['param_id'] = 35; //From Document
    //     $accountTransa['total'] = bcdiv($amount, 1, 2);

    //     $transid = Transaction::create($accountTransa)->id;

    //     $trash = ['type' => 24, 'from' => 35, 'id' => $transid];
    //     $transaction_id = Helper::transaction_id($trash);
    //     Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

    //     $expense['transaction_id'] = $transid;
    //     $expense['payment_method'] = $method; //DD
    //     $expense['date'] = $date;
    //     $expense['amount'] =  bcdiv($amount, 1, 2);
    //     $expense['remaining'] = 0;
    //     $expense['remarks'] = $remarks;

    //     $expId = IncomeExpense::create($expense);

    //     $doc_name = Params::find($doc_id)->label;

    //     if (!empty($id) && !empty($transid) && !empty($expId)) {
    //         $response['status'] = true;
    //         $response['msg'] = "$doc_name document has been renewed and is valid till " . date("d-m-Y", strtotime($till)) . " for Vehicle " . $vehicleMod->license_plate;
    //     } else {
    //         $response['status'] = false;
    //         $response['msg'] = "Sorry! Something went wrong.";
    //     }

    //     return response()->json($response);
    // }

    public function singleStore(Request $request)
    {

        \Log::info('SingleStore Request Parameters:', [
            'date' => $request->get('date'),
            'payment_date' => $request->get('payment_date'),
            'amount' => $request->get('amount'),
            'vendor_id' => $request->get('vendor'),
            'bank' => $request->get('bank'),
            'method' => $request->get('method'),
            'vehicle_id' => $request->get('vehicle_id'),
            'doc_id' => $request->get('doc_id'),
            'ddno' => $request->get('ddno'),
            'remarks' => $request->get('remarks'),
            'all_data' => $request->all()
        ]);
        // dd($request->all());
        $requestDate = !empty($request->get('date')) ? Carbon::createFromFormat('d-m-Y', $request->get('date')) : null;
        $paymentDate = !empty($request->get('payment_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('payment_date')) : null;
        $amount = $request->get('amount');
        $vendor_id = $request->get('vendor');
        $bank = $request->get('bank');
        $method = $request->get('method');
        $vehicle_id = $request->get('vehicle_id');
        $doc_id = $request->get('doc_id');
        $ddno = $request->get('ddno');
        $remarks = $request->get('remarks');

        $durationNameArray = [
            36 => 'ins_renew_duration', 
            37 => 'fitness_renew_duration', 
            38 => 'roadtax_renew_duration', 
            39 => 'permit_renew_duration', 
            40 => 'pollution_renew_duration'
        ];
        
        $durationUnitArray = [
            36 => 'insurance_duration_unit', 
            37 => 'fitness_duration_unit', 
            38 => 'roadtax_duration_unit', 
            39 => 'permit_duration_unit', 
            40 => 'pollution_duration_unit'
        ];
        
        $expiryKeyMap = [
            36 => 'ins_exp_date',
            37 => 'fitness_expdate',
            38 => 'road_expdate',
            39 => 'permit_expdate',
            40 => 'pollution_expdate'
        ];

        $vehicleMod = VehicleModel::find($vehicle_id);
        $driver_id = !empty($vehicleMod->driver) && !empty($vehicleMod->driver->assigned_driver) ? 
            $vehicleMod->driver->assigned_driver->id : null;

        // Get current expiry date
        $currentExpiryDate = $vehicleMod->getMeta($expiryKeyMap[$doc_id]);
        $currentExpiryDate = !empty($currentExpiryDate) ? 
            Carbon::createFromFormat('Y-m-d', $currentExpiryDate) : null;

        // Determine base date for calculations
        $baseDate = $requestDate;
        if ($currentExpiryDate && $currentExpiryDate->gt($requestDate)) {
            $baseDate = $currentExpiryDate;
        }

        // Calculate new expiry date
        $durationTime = $vehicleMod->getMeta($durationNameArray[$doc_id]);
        $durationUnit = $vehicleMod->getMeta($durationUnitArray[$doc_id]);
        $till = clone $baseDate;

        if ($durationUnit == 'years') {
            $till->addYears($durationTime)->subDay();
        } elseif ($durationUnit == 'months') {
            $till->addMonths($durationTime)->subDay();
        } else { // days
            $till->addDays($durationTime - 1);
        }

        // Add to Vehicle Documents
        $dataCreate = [
            'vehicle_id' => $vehicle_id,
            'driver_id' => $driver_id,
            'vendor_id' => $vendor_id,
            'param_id' => $doc_id,
            'date' => $baseDate->format('Y-m-d'),
            'payment_date' => $paymentDate ? $paymentDate->format('Y-m-d') : null,
            'till' => $till->format('Y-m-d'),
            'amount' => bcdiv($amount, 1, 2),
            'status' => 1,
            'remarks' => $remarks,
            'method' => $method,
            'ddno' => $ddno,
        ];
        
        $id = VehicleDocs::create($dataCreate)->id;

        // Update expiry date in vehicles_meta
        if (isset($expiryKeyMap[$doc_id])) {
            \DB::table('vehicles_meta')
                ->where('vehicle_id', $vehicle_id)
                ->where('key', $expiryKeyMap[$doc_id])
                ->where('type', 'string')
                ->update([
                    'value' => $till->format('Y-m-d'),
                    'updated_at' => now()
                ]);
        }

        // Transaction
        $accountTransa['from_id'] = $id;
        $accountTransa['type'] = 24;
        $accountTransa['bank_id'] = $bank;
        $accountTransa['param_id'] = 35;
        $accountTransa['total'] = bcdiv($amount, 1, 2);

        $transid = Transaction::create($accountTransa)->id;

        $trash = ['type' => 24, 'from' => 35, 'id' => $transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

        // Expense
        $expense['transaction_id'] = $transid;
        $expense['payment_method'] = $method;
        $expense['date'] = $baseDate->format('Y-m-d');
        $expense['amount'] = bcdiv($amount, 1, 2);
        $expense['remaining'] = 0;
        $expense['remarks'] = $remarks;

        $expId = IncomeExpense::create($expense);

        $doc_name = Params::find($doc_id)->label;

        if (!empty($id) && !empty($transid) && !empty($expId)) {
            \Log::info('Document renewal details:', [
                'vehicle_id' => $vehicle_id,
                'doc_id' => $doc_id,
                'meta_key' => $expiryKeyMap[$doc_id] ?? 'not found',
                'base_date' => $baseDate->format('Y-m-d'),
                'till_date' => $till->format('Y-m-d'),
                'current_expiry' => $currentExpiryDate ? $currentExpiryDate->format('Y-m-d') : 'none'
            ]);
            
            $response['status'] = true;
            $response['msg'] = "$doc_name document has been renewed and is valid till " . $till->format('d-m-Y') . 
                " for Vehicle " . $vehicleMod->license_plate;
        } else {
            $response['status'] = false;
            $response['msg'] = "Sorry! Something went wrong.";
        }

        return response()->json($response);
    }

    public function getNextDate(Request $request)
    {
        $requestDate = !empty($request->date) ? Carbon::createFromFormat('d-m-Y', $request->date) : null;
        $vehicle_id = $request->vehicle_id;
        $doc_id = $request->doc_id;
        $vdata = VehicleModel::find($vehicle_id);

        // Mapping arrays for meta keys
        $expiryKeyMap = [
            36 => 'ins_exp_date',
            37 => 'fitness_expdate',
            38 => 'road_expdate',
            39 => 'permit_expdate',
            40 => 'pollution_expdate'
        ];

        $durationNameArray = [
            36 => 'ins_renew_duration',
            37 => 'fitness_renew_duration',
            38 => 'roadtax_renew_duration',
            39 => 'permit_renew_duration',
            40 => 'pollution_renew_duration'
        ];

        $durationUnitArray = [
            36 => 'insurance_duration_unit',
            37 => 'fitness_duration_unit',
            38 => 'roadtax_duration_unit',
            39 => 'permit_duration_unit',
            40 => 'pollution_duration_unit'
        ];

        // Get the current expiry date for the document
        $currentExpiryDate = $vdata->getMeta($expiryKeyMap[$doc_id]);
        $currentExpiryDate = !empty($currentExpiryDate) ? Carbon::createFromFormat('Y-m-d', $currentExpiryDate) : null;

        // Determine which date to use as base for calculation
        $baseDate = $requestDate;
        if ($currentExpiryDate && $currentExpiryDate->gt($requestDate)) {
            $baseDate = $currentExpiryDate;
        }

        // Get duration settings
        $durationTime = $vdata->getMeta($durationNameArray[$doc_id]);
        $durationUnit = $vdata->getMeta($durationUnitArray[$doc_id]);

        // Calculate new expiry date
        $newExpiryDate = clone $baseDate;
        
        if ($durationUnit == 'years') {
            $newExpiryDate->addYears($durationTime)->subDay();
        } elseif ($durationUnit == 'months') {
            $newExpiryDate->addMonths($durationTime)->subDay();
        } else { // days
            $newExpiryDate->addDays($durationTime - 1);
        }

        // Format response
        $label = "<label> Valid Till : " . $newExpiryDate->format("d-m-Y") . "</label>";
        return $label;
    }

    public function getNextDateEdit(Request $request)
    {
        // return $request->all();
        $date = !empty($request->date) ? date("Y-m-d", strtotime($request->date)) : null;
        $vehicle_id = $request->vehicle_id;
        $doc_id = $request->doc_id;
        $vdata = VehicleModel::find($vehicle_id);
        $durationNameArray = [36 => 'ins_renew_duration', 37 => 'fitness_renew_duration', 38 => 'roadtax_renew_duration', 39 => 'permit_renew_duration', 40 => 'pollution_renew_duration'];
        $durationUnitArray = [36 => 'insurance_duration_unit', 37 => 'fitness_duration_unit', 38 => 'roadtax_duration_unit', 39 => 'permit_duration_unit', 40 => 'pollution_duration_unit'];
        $durationTime = $vdata->getMeta($durationNameArray[$doc_id]);
        $durationUnit = $vdata->getMeta($durationUnitArray[$doc_id]);
        $date = new Carbon($date);

        if ($durationUnit == 'years') {
            $date->addYears($durationTime)->subDay();
        } elseif ($durationUnit == 'months') {
            $date->addMonths($durationTime)->subDay();
        } else { // days
            $date->addDays($durationTime - 1);
        }

        // $newDate = $date->addDays($durationDays);
        $label = "<label> Valid Till : ";
        $label .= $date->format("d-m-Y");
        $label .= "</label>";
        return $label;
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
        // dd($request->method);
        $duration = [36 => 'ins_renew_duration', 37 => 'fitness_renew_duration', 38 => 'roadtax_renew_duration', 39 => 'permit_renew_duration', 40 => 'pollution_renew_duration'];
        foreach ($request->date as $k => $vdoc) {
            $vehicle_id = $k; //vehicle_id
            $vehicleMod = VehicleModel::find($vehicle_id);
            // dd($vehicleMod->getMeta());
            $driver_id = !empty($vehicleMod->driver) && !empty($vehicleMod->driver->assigned_driver) ? $vehicleMod->driver->assigned_driver->id : null; //driver_id

            // dd($k);
            foreach ($vdoc as $key => $val) {
                // dd($key);
                //list out everything
                $startDate = new Carbon($request->date[$k][$key]);
                $startDate->addDays($vehicleMod->getMeta($duration[$key])); //till addMonths addYears
                $bank = $request->bank[$k][$key]; //bank id
                $dataCreate = [
                    'vehicle_id' => $vehicle_id,
                    'driver_id' => $driver_id,
                    'vendor_id' => $request->vendor[$k][$key],
                    'param_id' => $key, //document param id
                    'date' => $val, //on date
                    'till' => $startDate,
                    'amount' => $request->amount[$k][$key],
                    'status' => 1,
                    'remarks' => null,
                    'method' => $request->method[$k][$key],
                    'ddno' => $request->ddno[$k][$key],
                ];
                $id = VehicleDocs::create($dataCreate)->id;

                //transaction
                $accountTransa['from_id'] = $id; //Vehicle Docs ID
                $accountTransa['type'] = 24; // Debit 
                $accountTransa['bank_id'] = $bank; // Bank ID
                $accountTransa['param_id'] = 35; //From Document
                $accountTransa['total'] = $request->amount[$k][$key];

                $transid = Transaction::create($accountTransa)->id;

                $trash = ['type' => 24, 'from' => 35, 'id' => $transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                $expense['transaction_id'] = $transid;
                $expense['payment_method'] = 17; //DD
                $expense['date'] = date("Y-m-d H:i:s");
                $expense['amount'] =  $request->amount[$k][$key];
                $expense['remaining'] = 0;
                $expense['remarks'] = null;

                IncomeExpense::create($expense);
            }
        }
        return redirect()->route('vehicle-docs.index');
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
        $doc = VehicleDocs::findOrFail($id);
        
        $vehiArray = array();
        $vehicles = VehicleModel::select("id", DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))
            ->where('in_service', 1)
            ->get();

        foreach ($vehicles as $v) {
            // Always include the vehicle from the original document
            if ($v->id == $doc->vehicle_id) {
                $vehiArray[$v->id] = $v->name;
                continue;
            }

            $insu_expdate = $v->getMeta('ins_exp_date');
            $insu_dur = $v->getMeta('ins_renew_duration');
            $fitness_expdate = $v->getMeta('fitness_expdate');
            $fitness_dur = $v->getMeta('fitness_renew_duration');
            $roadtax_expdate = $v->getMeta('road_expdate');
            $roadtax_dur = $v->getMeta('roadtax_renew_duration');
            $permit_expdate = $v->getMeta('permit_expdate');
            $permit_dur = $v->getMeta('permit_renew_duration');
            $pollution_expdate = $v->getMeta('pollution_expdate');
            $pollution_dur = $v->getMeta('pollution_renew_duration');

            if (((!empty($insu_dur) && !empty($insu_expdate)) || 
                (!empty($fitness_dur) && !empty($fitness_expdate)) || 
                (!empty($roadtax_dur) && !empty($roadtax_expdate)) || 
                (!empty($permit_dur) && !empty($permit_expdate)) || 
                (!empty($pollution_dur) && !empty($pollution_expdate))) && 
                Helper::checkEligibleRenewalVehicle($v->id)->status) {
                $v->is_renewable = 1;
                $vehiArray[$v->id] = $v->name;
            } else {
                $v->is_renewable = null;
            }
        }

        $data['vehicles'] = $vehiArray;
        $data['method'] = Params::where('code', "PaymentMethod")->where('id', '!=', '16')->pluck('label', 'id');
        
        // Vendor dropdown
        $data['vendors'] = Vendor::where('type', 'Document')->pluck('name', 'id');
        
        // Bank accounts dropdown
        $data['bankAccount'] = BankAccount::select("id", DB::raw("CONCAT(bank,'(',account_no,')') as bank"))->pluck('bank', 'id');
        
        // Document details
        $data['doc'] = $doc;
        $data['edit'] = true; // Flag to differentiate between create and edit views

        return view('vehicle_docs.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        $vehicleDoc = VehicleDocs::findOrFail($id);
    
        $date = !empty($request->get('date')) ? date("Y-m-d", strtotime($request->get('date'))) : null;
        $payment_date = !empty($request->get('payment_date')) ? date("Y-m-d", strtotime($request->get('payment_date'))) : null;
        $amount = $request->get('amount');
        $vendor_id = $request->get('vendor');
        $bank = $request->get('bank');
        $method = $request->get('method');
        $vehicle_id = $request->get('vehicle_id');
        $doc_id = $request->get('doc_id');
        $ddno = $request->get('ddno');
        $remarks = $request->get('remarks');
    
        $durationNameArray = [36 => 'ins_renew_duration', 37 => 'fitness_renew_duration', 38 => 'roadtax_renew_duration', 39 => 'permit_renew_duration', 40 => 'pollution_renew_duration'];
        $durationUnitArray = [36 => 'insurance_duration_unit', 37 => 'fitness_duration_unit', 38 => 'roadtax_duration_unit', 39 => 'permit_duration_unit', 40 => 'pollution_duration_unit'];
    
        $vehicleMod = VehicleModel::find($vehicle_id);
        $driver_id = !empty($vehicleMod->driver) && !empty($vehicleMod->driver->assigned_driver) ? $vehicleMod->driver->assigned_driver->id : null;
    
        $durationTime = $vehicleMod->getMeta($durationNameArray[$doc_id]);
        $durationUnit = $vehicleMod->getMeta($durationUnitArray[$doc_id]);
        $till = new Carbon($date);
    
        if ($durationUnit == 'years') {
            $till->addYears($durationTime)->subDay();
        } elseif ($durationUnit == 'months') {
            $till->addMonths($durationTime)->subDay();
        } else { // days
            $till->addDays($durationTime - 1);
        }
    
        // Update Vehicle Document
        $dataUpdate = [
            'vehicle_id' => $vehicle_id,
            'driver_id' => $driver_id,
            'vendor_id' => $vendor_id,
            'param_id' => $doc_id,
            'date' => $date,
            'payment_date' => $payment_date,  // Add this line
            'till' => $till,
            'amount' => bcdiv($amount, 1, 2),
            'status' => 1,
            'remarks' => $remarks,
            'method' => $method,
            'ddno' => $ddno,
        ];
        $vehicleDoc->update($dataUpdate);
    
        // Update Transaction
        $transaction = $vehicleDoc->transaction;
        if ($transaction) {
            $accountTransa['bank_id'] = $bank;
            $accountTransa['total'] = bcdiv($amount, 1, 2);
            $transaction->update($accountTransa);
    
            // Update Income/Expense
            $expense = $transaction->income_Expense;
            if ($expense) {
                $expenseUpdate = [
                    'payment_method' => $method,
                    'date' => $date,
                    'amount' => bcdiv($amount, 1, 2),
                    'remarks' => $remarks,
                ];
                $expense->update($expenseUpdate);
            }
        }
    
        return redirect()->route('vehicle-docs.index')->with('success', 'Vehicle Document updated successfully');
    }

    // public function update(Request $request, $id)
    // {
    //     $vehicleDoc = VehicleDocs::findOrFail($id);

    //     $requestDate = !empty($request->get('date')) ? Carbon::createFromFormat('d-m-Y', $request->get('date')) : null;
    //     $paymentDate = !empty($request->get('payment_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('payment_date')) : null;
    //     $amount = $request->get('amount');
    //     $vendor_id = $request->get('vendor');
    //     $bank = $request->get('bank');
    //     $method = $request->get('method');
    //     $vehicle_id = $request->get('vehicle_id');
    //     $doc_id = $request->get('doc_id');
    //     $ddno = $request->get('ddno');
    //     $remarks = $request->get('remarks');

    //     $durationNameArray = [
    //         36 => 'ins_renew_duration', 
    //         37 => 'fitness_renew_duration', 
    //         38 => 'roadtax_renew_duration', 
    //         39 => 'permit_renew_duration', 
    //         40 => 'pollution_renew_duration'
    //     ];
        
    //     $durationUnitArray = [
    //         36 => 'insurance_duration_unit', 
    //         37 => 'fitness_duration_unit', 
    //         38 => 'roadtax_duration_unit', 
    //         39 => 'permit_duration_unit', 
    //         40 => 'pollution_duration_unit'
    //     ];

    //     $expiryKeyMap = [
    //         36 => 'ins_exp_date',
    //         37 => 'fitness_expdate',
    //         38 => 'road_expdate',
    //         39 => 'permit_expdate',
    //         40 => 'pollution_expdate'
    //     ];

    //     $vehicleMod = VehicleModel::find($vehicle_id);
    //     $driver_id = !empty($vehicleMod->driver) && !empty($vehicleMod->driver->assigned_driver) ? 
    //         $vehicleMod->driver->assigned_driver->id : null;

    //     // Get current expiry date
    //     $currentExpiryDate = $vehicleMod->getMeta($expiryKeyMap[$doc_id]);
    //     $currentExpiryDate = !empty($currentExpiryDate) ? 
    //         Carbon::createFromFormat('Y-m-d', $currentExpiryDate) : null;

    //     // Determine base date for calculations
    //     $baseDate = $requestDate;
    //     if ($currentExpiryDate && $currentExpiryDate->gt($requestDate)) {
    //         $baseDate = $currentExpiryDate;
    //     }

    //     // Calculate new expiry date
    //     $durationTime = $vehicleMod->getMeta($durationNameArray[$doc_id]);
    //     $durationUnit = $vehicleMod->getMeta($durationUnitArray[$doc_id]);
    //     $till = clone $baseDate;

    //     if ($durationUnit == 'years') {
    //         $till->addYears($durationTime)->subDay();
    //     } elseif ($durationUnit == 'months') {
    //         $till->addMonths($durationTime)->subDay();
    //     } else { // days
    //         $till->addDays($durationTime - 1);
    //     }

    //     // Update Vehicle Document
    //     $dataUpdate = [
    //         'vehicle_id' => $vehicle_id,
    //         'driver_id' => $driver_id,
    //         'vendor_id' => $vendor_id,
    //         'param_id' => $doc_id,
    //         'date' => $baseDate->format('Y-m-d'),
    //         'payment_date' => $paymentDate ? $paymentDate->format('Y-m-d') : null,
    //         'till' => $till->format('Y-m-d'),
    //         'amount' => bcdiv($amount, 1, 2),
    //         'status' => 1,
    //         'remarks' => $remarks,
    //         'method' => $method,
    //         'ddno' => $ddno,
    //     ];
    //     $vehicleDoc->update($dataUpdate);

    //     // Update expiry date in vehicles_meta
    //     if (isset($expiryKeyMap[$doc_id])) {
    //         \DB::table('vehicles_meta')
    //             ->where('vehicle_id', $vehicle_id)
    //             ->where('key', $expiryKeyMap[$doc_id])
    //             ->where('type', 'string')
    //             ->update([
    //                 'value' => $till->format('Y-m-d'),
    //                 'updated_at' => now()
    //             ]);
    //     }

    //     // Update Transaction
    //     $transaction = $vehicleDoc->transaction;
    //     if ($transaction) {
    //         $accountTransa['bank_id'] = $bank;
    //         $accountTransa['total'] = bcdiv($amount, 1, 2);
    //         $transaction->update($accountTransa);

    //         // Update Income/Expense
    //         $expense = $transaction->income_Expense;
    //         if ($expense) {
    //             $expenseUpdate = [
    //                 'payment_method' => $method,
    //                 'date' => $baseDate->format('Y-m-d'),
    //                 'amount' => bcdiv($amount, 1, 2),
    //                 'remarks' => $remarks,
    //             ];
    //             $expense->update($expenseUpdate);
    //         }
    //     }

    //     \Log::info('Document update details:', [
    //         'vehicle_id' => $vehicle_id,
    //         'doc_id' => $doc_id,
    //         'meta_key' => $expiryKeyMap[$doc_id] ?? 'not found',
    //         'base_date' => $baseDate->format('Y-m-d'),
    //         'till_date' => $till->format('Y-m-d'),
    //         'current_expiry' => $currentExpiryDate ? $currentExpiryDate->format('Y-m-d') : 'none'
    //     ]);

    //     return redirect()->route('vehicle-docs.index')->with('success', 'Vehicle Document updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            \Log::info('Attempting to delete vehicle document with ID: ' . $id);

            $vehicleDoc = VehicleDocs::findOrFail($id);
            
            // Delete associated transaction if exists
            if ($vehicleDoc->transaction) {
                // Delete associated income/expense record
                $vehicleDoc->transaction->income_Expense()->delete();
                
                // Delete the transaction
                $vehicleDoc->transaction()->delete();
            }

            // Soft delete the vehicle document
            $vehicleDoc->delete();

            \Log::info('Vehicle document deleted successfully: ' . $id);

            return redirect()->route('vehicle-docs.index')
                ->with('success', 'Vehicle document deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting vehicle document: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());

            return redirect()->route('vehicle-docs.index')
                ->with('error', 'Unable to delete the vehicle document. ' . $e->getMessage());
        }
    }

    public function view_event($id)
    {
        $data['row'] = VehicleDocs::find($id);
        // dd($data);
        return view('vehicle_docs.view_event', $data);
    }

    // public function upcomingreport() {
    //     $param_id = "upcoming-renewals";
    //     Helper::housekeeping($param_id);
    //     $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
    //     $collection = collect((array)json_decode($put));
    //     // $collection = Bookings::hydrate($object);
    //     // dd($collection);
    //     $data['data'] = $collection->flatten();   // get rid of unique_id_XXX
    //     $data['vehicle_Array'] = $data['data']->pluck('vehicle_id','vid');
    // 	// dd(FuelType::pluck('fuel_name','id'));
    // 	// dd($data['fuel']);
    // 	$data['vehicle_id'] = "";
    // 	$data['documents'] = Params::where('code','RenewDocuments')->pluck('label','id');
    // 	$data['date1'] = null;
    // 	$data['date2'] = null;
    // 	$data['request'] = null;
    // 	// dd($data);
    // 	return view('vehicle_docs.upcomingreport', $data);
    // }
    // public function upcomingreport_post(Request $request) {
    //     // dd($request->all());
    //     // dd(Helper::upcomingRenewals());
    //     $param_id = "upcoming-renewals";
    //     Helper::housekeeping($param_id);
    //     $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
    //     $collection = collect((array)json_decode($put));
    //     // $collection = Bookings::hydrate($object);
    //     // dd($collection);
    //     $data['data'] = $collection->flatten();   // get rid of unique_id_XXX
    //     $data['vehicle_Array'] = $data['data']->pluck('vehicle_id','vid');
    //     $data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
    //     $data['document_id'] = $document_id = $request->get('documents');
    //     // dd($data['data']->where('till','!=',null)->sortBy('till'));
    //     if($request->get('date1')==null){
    //         // echo 1;
    // 		$start = !$data['data']->isEmpty() ? $data['data']->where('till','!=',null)->sortBy('till')->first()->till: '';
    //     }else
    // 		$start = date('Y-m-d', strtotime($request->get('date1')));

    // 	if($request->get('date2')==null)
    // 		$end = !$data['data']->isEmpty() ? $data['data']->sortByDesc('till')->first()->till : '';
    // 	else
    // 		$end = date('Y-m-d', strtotime($request->get('date2')));

    //     // dd($start,$end);
    //     $data['data'] = $data['data']->where('till','!=',null)->sortBy('daysleft');
    //     // dd($data);



    //     // dd($data['data']);

    // 	if (!empty($vehicle_id) && !empty($document_id)) {
    // 		$data['data'] = $data['data']->where('vid',$vehicle_id)->where('pid',$document_id)->whereBetween('till', [$start, $end]);
    // 	}
    //     else if (!empty($vehicle_id) && empty($document_id)) {
    // 		$data['data'] = $data['data']->where('vid',$vehicle_id)->whereBetween('till', [$start, $end]);
    // 	}
    //     else if (empty($vehicle_id) && !empty($document_id)) {
    // 		$data['data'] = $data['data']->where('pid',$document_id)->whereBetween('till', [$start, $end]);
    // 	}
    // 	else {
    // 		$data['data'] = $data['data']->whereBetween('till', [$start, $end]);
    // 	}
    //     $data['data'] = $data['data']->flatten()->sortBy('daysleft');
    //     // dd($data['data']->flatten());
    //     // dd($data['data']);
    //     foreach($data['data'] as $dd){
    //         $dd->vehicleObj = VehicleModel::select("id")->where("id",$dd->vid)->first();
    //     }
    //     $data['docparamArray'] = [
    //         36=>['ins_renew_duration','ins_renew_amount','ins_exp_date'],
    //         37=>['fitness_renew_duration','fitness_renew_amount','fitness_expdate'],
    //         38=>['roadtax_renew_duration','roadtax_renew_amount','road_expdate'],
    //         39=>['permit_renew_duration','permit_renew_amount','permit_expdate'],
    //         40=>['pollution_renew_duration','pollution_renew_amount','pollution_expdate']
    //     ];
    // 	$data['documents'] = Params::where('code','RenewDocuments')->pluck('label','id');
    // 	$data['result'] = "";
    // 	$data['request'] = $request->all();
    // 	$data['dates'] = [$start,$end];
    // 	$data['date1'] = $start;
    // 	$data['date2'] = $end;
    // 	// dd($data['data']->first());
    // 	return view('vehicle_docs.upcomingreport', $data);
    // }
    // public function print_upcomingreport(Request $request) {
    //    // dd($request->all());
    //     // dd(Helper::upcomingRenewals());
    //     $param_id = "upcoming-renewals";
    //     Helper::housekeeping($param_id);
    //     $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
    //     $collection = collect((array)json_decode($put));
    //     // $collection = Bookings::hydrate($object);
    //     // dd($collection);
    //     $data['data'] = $collection->flatten();   // get rid of unique_id_XXX
    //     $data['vehicle_Array'] = $data['data']->pluck('vehicle_id','vid');
    //     $data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
    //     $data['document_id'] = $document_id = $request->get('documents');
    //     $data['vehicle_data'] = !empty($vehicle_id) ? VehicleModel::find($vehicle_id) : null;
    //     // $data['vehicle_doc'] = !empty($vehicle_id) ? VehicleDocs::find($vehicle_id) : null;
    //     // dd($data['data']->where('till','!=',null)->sortBy('till'));
    //     if($request->get('date1')==null){
    //         // echo 1;
    // 		$start = !$data['data']->isEmpty() ? $data['data']->where('till','!=',null)->sortBy('till')->first()->till: '';
    //     }else
    // 		$start = date('Y-m-d', strtotime($request->get('date1')));

    // 	if($request->get('date2')==null)
    // 		$end = !$data['data']->isEmpty() ? $data['data']->sortByDesc('till')->first()->till : '';
    // 	else
    // 		$end = date('Y-m-d', strtotime($request->get('date2')));

    //     // dd($start,$end);
    //     $data['data'] = $data['data']->where('till','!=',null)->sortBy('daysleft');
    //     // dd($data);



    //     // dd($data['data']);

    // 	if (!empty($vehicle_id) && !empty($document_id)) {
    // 		$data['data'] = $data['data']->where('vid',$vehicle_id)->where('pid',$document_id)->whereBetween('till', [$start, $end]);
    // 	}
    //     else if (!empty($vehicle_id) && empty($document_id)) {
    // 		$data['data'] = $data['data']->where('vid',$vehicle_id)->whereBetween('till', [$start, $end]);
    // 	}
    //     else if (empty($vehicle_id) && !empty($document_id)) {
    // 		$data['data'] = $data['data']->where('pid',$document_id)->whereBetween('till', [$start, $end]);
    // 	}
    // 	else {
    // 		$data['data'] = $data['data']->whereBetween('till', [$start, $end]);
    // 	}
    //     $data['data'] = $data['data']->flatten()->sortBy('daysleft');
    //     // dd($data['data']->flatten());
    //     // dd($data['data']);
    //     foreach($data['data'] as $dd){
    //         $dd->vehicleObj = VehicleModel::select("id")->where("id",$dd->vid)->first();
    //     }
    //     $data['docparamArray'] = [
    //         36=>['ins_renew_duration','ins_renew_amount','ins_exp_date'],
    //         37=>['fitness_renew_duration','fitness_renew_amount','fitness_expdate'],
    //         38=>['roadtax_renew_duration','roadtax_renew_amount','road_expdate'],
    //         39=>['permit_renew_duration','permit_renew_amount','permit_expdate'],
    //         40=>['pollution_renew_duration','pollution_renew_amount','pollution_expdate']
    //     ];
    // 	$data['documents'] = Params::where('code','RenewDocuments')->pluck('label','id');
    // 	$data['result'] = "";
    // 	$data['request'] = $request->all();
    // 	$data['dates'] = [$start,$end];
    // 	$data['date1'] = $start;
    // 	$data['date2'] = $end;
    // 	$data['date'] = date("Y-m-d");
    //     $data['from_date'] = Helper::getCanonicalDate($start);
    // 	$data['to_date'] = Helper::getCanonicalDate($end);
    // 	// dd($data['data']->first());
    // 	return view('vehicle_docs.print-upcomingreport', $data);
    // }

}
