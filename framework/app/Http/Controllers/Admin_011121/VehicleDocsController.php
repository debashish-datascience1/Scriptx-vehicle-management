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

class VehicleDocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index['docs'] = VehicleDocs::orderBy('id','DESC')->get();
        // $index['docs'] = [];
        // dd($index);
        return view('vehicle_docs.index',$index);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehiArray = array();
        $vehicles =  VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->where('in_service',1)->get();
        foreach($vehicles as $v){
            $insu_dur = $v->getMeta('ins_renew_duration');
            $insu_amt = $v->getMeta('ins_renew_amount');
            $fitness_dur = $v->getMeta('fitness_renew_duration');
            $fitness_amt = $v->getMeta('fitness_renew_amount');
            $roadtax_dur = $v->getMeta('roadtax_renew_duration');
            $roadtax_amt = $v->getMeta('roadtax_renew_amount');
            $permit_dur = $v->getMeta('permit_renew_duration');
            $permit_amt = $v->getMeta('permit_renew_amount');
            $pollution_dur = $v->getMeta('pollution_renew_duration');
            $pollution_amt = $v->getMeta('pollution_renew_amount');
            if((!empty($insu_dur) && !empty($insu_amt)) || (!empty($fitness_dur) && !empty($fitness_amt)) || (!empty($roadtax_dur) && !empty($roadtax_amt)) || (!empty($permit_dur) && !empty($permit_amt)) || (!empty($pollution_dur) && !empty($pollution_amt))){
                // dd($v);
                $v->is_renewable = 1;
                $vehiArray[$v->id] = $v->name;
                // dd($v);
            }else{
                $v->is_renewable = null;
            }
            // !is_null($v->is_renewable) ?? dd($v) ;
        }
        // dd($vehicles->toArray());
        // dd($vehiArray);
        $data['vehicles'] = $vehiArray;
        $data['method'] = Params::where('code','PaymentMethod')->pluck('label','id');
        return view('vehicle_docs.create',$data);
    }

    public function renewVehicles(Request $request){
        // dd($request->id);
        // if($request->id=='null') return false;
        $ids = explode(",",$request->id);
        // dd($ids);
        $data['vehicles'] = VehicleModel::whereIn('id',$ids)->get();
        $data['vendors'] = Vendor::where('type','Document')->pluck('name','id');
        $data['bankAccount'] = BankAccount::where('id','!=',1)->pluck('bank','id');
        $data['docparams'] = Params::where('code','RenewDocuments')->get();
        $data['docparamArray'] = [
                                    36=>['ins_renew_duration','ins_renew_amount','ins_exp_date'],
                                    37=>['fitness_renew_duration','fitness_renew_amount','fitness_expdate'],
                                    38=>['roadtax_renew_duration','roadtax_renew_amount','road_expdate'],
                                    39=>['permit_renew_duration','permit_renew_amount','permit_expdate'],
                                    40=>['pollution_renew_duration','pollution_renew_amount','pollution_expdate']
                                ];
        // dd($data);
        return view('vehicle_docs.renew_list',$data);
    }
    
    public function getNextDate(Request $request){
        // return $request->all();
        $date = $request->date;
        $vehicle_id = $request->vehicle_id;
        $doc_id = $request->doc_id;
        $vdata = VehicleModel::find($vehicle_id);
        $durationNameArray = [36=>'ins_renew_duration',37=>'fitness_renew_duration',38=>'roadtax_renew_duration',39=>'permit_renew_duration',40=>'pollution_renew_duration'];
        $durationDays = $vdata->getMeta($durationNameArray[$doc_id]);
        $date = new Carbon($date);
        $newDate = $date->addDays($durationDays);
        $label = "<label> Valid Till : ";
        $label .= $date->format("d-m-Y");
        $label.="</label>";
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
        $duration = [36=>'ins_renew_duration',37=>'fitness_renew_duration',38=>'roadtax_renew_duration',39=>'permit_renew_duration',40=>'pollution_renew_duration'];
        foreach($request->date as $k=>$vdoc){
            $vehicle_id = $k; //vehicle_id
            $vehicleMod = VehicleModel::find($vehicle_id);
            // dd($vehicleMod->getMeta());
            $driver_id = !empty($vehicleMod->driver) && !empty($vehicleMod->driver->assigned_driver) ? $vehicleMod->driver->assigned_driver->id : null; //driver_id

            // dd($k);
            foreach($vdoc as $key=>$val){
                // dd($key);
                //list out everything
                $startDate = new Carbon($request->date[$k][$key]);
                $startDate->addDays($vehicleMod->getMeta($duration[$key]));//till
                $bank = $request->bank[$k][$key]; //bank id
                $dataCreate = [
                    'vehicle_id'=> $vehicle_id,
                    'driver_id' => $driver_id,
                    'vendor_id' => $request->vendor[$k][$key],
                    'param_id' => $key,//document param id
                    'date' => $val,//on date
                    'till' => $startDate,
                    'amount' => $request->amount[$k][$key],
                    'status' => $request->status[$k][$key],
                    'remarks' => $request->remarks[$k][$key],
                    'method' => $request->method,
                    'ddno' => $request->ddno,
                ];
                $id = VehicleDocs::create($dataCreate)->id;

                //transaction
                $accountTransa['from_id'] = $id; //Vehicle Docs ID
                $accountTransa['type'] = 24;// Debit 
                $accountTransa['bank_id'] = $bank;// Bank ID
                $accountTransa['param_id'] = 35; //From Document
                $accountTransa['total'] = $request->amount[$k][$key];

                $transid = Transaction::create($accountTransa)->id;

                $trash = ['type'=>24,'from'=>35,'id'=>$transid];
                $transaction_id = Helper::transaction_id($trash);
                Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);

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

    public function view_event($id){
        $data['row'] = VehicleDocs::find($id);
        // dd($data);
        return view('vehicle_docs.view_event',$data);
    }

    public function report() {
		// dd($_POST);
		
		$data['vehicles'] = VehicleModel::select(
            DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
            ->pluck('vehicle_name', 'id');
		// dd(FuelType::pluck('fuel_name','id'));
		// dd($data['fuel']);
		$data['vehicle_id'] = "";
		$data['documents'] = Params::where('code','RenewDocuments')->pluck('label','id');
		$data['date1'] = null;
		$data['date2'] = null;
		$data['request'] = null;
		// dd($data);
		return view('vehicle_docs.report', $data);
	}

	public function report_post(Request $request) {
		
		// dd($request->all());

		$data['vehicles'] = VehicleModel::select(
            DB::raw("CONCAT(make,'-',model,'-',license_plate) AS vehicle_name"),'id')
            ->pluck('vehicle_name', 'id');

        $data['vehicle_id'] = $vehicle_id = $request->get('vehicle_id');
        $data['documents'] = $documents = $request->get('documents');

		if($request->get('date1')==null)
			$start = VehicleDocs::orderBy('date','asc')->take(1)->exists() ? VehicleDocs::orderBy('date','asc')->take(1)->first('date')->date : '';
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = VehicleDocs::orderBy('date','desc')->take(1)->exists() ? VehicleDocs::orderBy('date','desc')->take(1)->first('date')->date : '';
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			
		// dd($start);
		// dd($end);
		

		
		if (!empty($vehicle_id) && !empty($documents)) {
			$data['docs'] = VehicleDocs::where(['vehicle_id'=>$vehicle_id,'param_id'=>$documents])->whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
        else if (!empty($vehicle_id) && empty($documents)) {
			$data['docs'] = VehicleDocs::where(['vehicle_id'=>$vehicle_id])->whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
        else if (empty($vehicle_id) && !empty($documents)) {
			$data['docs'] = VehicleDocs::where(['param_id'=>$documents])->whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
		else {
			$data['docs'] = VehicleDocs::whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
        // dd($data);

		// Helper::totalBookingIncome($vehicle_id);
		// $data['details'] = WorkOrders::select(['vendor_id', DB::raw('sum(price) as total')])->whereBetween('created_at', [$start, $end])->whereIn('vehicle_id', $vehicle_ids)->groupBy('vendor_id')->get();
		// dd();
		$data['documents'] = Params::where('code','RenewDocuments')->pluck('label','id');
		$data['result'] = "";
		$data['request'] = $request->all();
		$data['dates'] = [$start,$end];
		$data['date1'] = $start;
		$data['date2'] = $end;
		// dd($data);
		return view('vehicle_docs.report', $data);
	}

	public function print_report(Request $request) {
		
		// dd($request->all());

		$data['vehicle'] = $vehicle= VehicleModel::find($request->get('vehicle_id'));
		$data['docs'] = $docs= VehicleModel::find($request->get('documents'));

        

		if($request->get('date1')==null)
			$start = VehicleDocs::orderBy('date','asc')->take(1)->exists() ? VehicleDocs::orderBy('date','asc')->take(1)->first('date')->date : '';
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = VehicleDocs::orderBy('date','desc')->take(1)->exists() ? VehicleDocs::orderBy('date','desc')->take(1)->first('date')->date : '';
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			
		// dd($start);
		// dd($end);
		

		
		if (!empty($vehicle) && !empty($docs)) {
			$data['docs'] = VehicleModel::where(['vehicle_id'=>$vehicle->id,'param_id'=>$docs->id])->whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
        else if (!empty($vehicle) && empty($docs)) {
			$data['docs'] = VehicleModel::where(['vehicle_id'=>$vehicle->id])->whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
        else if (empty($vehicle) && !empty($docs)) {
			$data['docs'] = VehicleDocs::where(['param_id'=>$docs->id])->whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
		else {
			$data['docs'] = VehicleDocs::whereBetween('date', [$start, $end])->orderBy('id','DESC')->get();
		}
        // dd($data);

		
		$data['documents'] = Params::where('code','RenewDocuments')->pluck('label','id');
		$data['result'] = "";
		$data['date'] = date("Y-m-d H:i:s");
		$data['requset'] = $request->all();
		$data['dates'] = [$start,$end];
		$data['from_date'] = Helper::getCanonicalDate($start);
		$data['to_date'] = Helper::getCanonicalDate($end);
		// dd($data);
       
		return view('vehicle_docs.print-report', $data);
	}
}