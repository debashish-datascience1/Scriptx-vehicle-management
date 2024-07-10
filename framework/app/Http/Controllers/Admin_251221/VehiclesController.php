<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\InsuranceRequest;
use App\Http\Requests\VehicleRequest;
use App\Http\Requests\VehiclReviewRequest;
use App\Model\AdvanceDriver;
use App\Model\Bookings;
use App\Model\DriverLogsModel;
use App\Model\DriverVehicleModel;
use App\Model\Expense;
use App\Model\FuelModel;
use App\Model\FuelType;
use App\Model\IncomeModel;
use App\Model\PartsCategoryModel;
use App\Model\PartsDetails;
use App\Model\PartsModel;
use App\Model\PartsUsedModel;
use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\VehicleGroupModel;
use App\Model\VehicleModel;
use App\Model\VehicleReviewModel;
use App\Model\VehicleTypeModel;
use App\Model\WorkOrders;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Importer;
use Redirect;
use DB;
use Helper;

class VehiclesController extends Controller
{

    public function importVehicles(ImportRequest $request)
    {
        // dd(Auth::user());
        $file = $request->excel;
        $destinationPath = './assets/samples/'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $file->move($destinationPath, $fileName);
        $excel = Importer::make('Excel');
        $excel->load('assets/samples/' . $fileName);
        $collection = $excel->getCollection()->toArray();
        // echo $fileName;
        array_shift($collection);
        // dd($collection);
        foreach ($collection as $vehicle) {
            $id = VehicleModel::create([
                'make' => $vehicle[0],
                'model' => $vehicle[1],
                'year' => $vehicle[2],
                'mileage'=>$vehicle[3],
                'int_mileage' => $vehicle[4],
                // 'reg_exp_date' => date('Y-m-d', strtotime($vehicle[5])),
                'engine_type' => ucwords(strtolower($vehicle[5])),
                // 'horse_power' => $vehicle[6],
                'color' => $vehicle[6],
                // 'vin' => $vehicle[9],
                'engine_no' => $vehicle[7],
                'chassis_no' => $vehicle[8],
                'license_plate' => $vehicle[9],
                // 'lic_exp_date' => date('Y-m-d', strtotime($vehicle[11])),
                'user_id' => Auth::id(),
                'group_id' => Auth::user()->group_id,
            ])->id;
            
            // $meta = VehicleModel::find($id);
            // $abc =date("Y-m-d",strtotime($collection[0][11]));
            
            // $meta->setMeta([
            //     'ins_number' => (isset($vehicle[10])) ? $vehicle[10] : "",
            //     'ins_exp_date' => (isset($vehicle[11]) && $vehicle[11] != null) ? date('Y-m-d', strtotime($vehicle[11])) : "",
            //     'documents' => "",
            // ]);
            // dd($meta);
            // $meta->average = $vehicle[3];
            // $meta->save();
        }
        return back();
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $index['data'] = VehicleModel::orderBy('id', 'desc')->get();
        } else {
            $index['data'] = VehicleModel::where('group_id', $user->group_id)->orderBy('id', 'desc')->get();
        }

        return view("vehicles.index", $index);
    }

    public function driver_logs()
    {
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $vehicle_ids = VehicleModel::select('id')->get('id')->pluck('id')->toArray();

        } else {
            $vehicle_ids = VehicleModel::select('id')->where('group_id', $user->group_id)->get('id')->pluck('id')->toArray();
        }
        $data['logs'] = DriverLogsModel::whereIn('vehicle_id', $vehicle_ids)->get();
        return view('vehicles.driver_logs', $data);
    }

    public function create()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $index['groups'] = VehicleGroupModel::all();
        } else {
            $index['groups'] = VehicleGroupModel::where('id', Auth::user()->group_id)->get();
        }
        $index['types'] = VehicleTypeModel::all();
        return view("vehicles.create", $index);
    }
    public function destroy(Request $request)
    {
        $vehicle = VehicleModel::find($request->get('id'));
        if ($vehicle->driver_id) {
            $driver = User::find($vehicle->driver_id);
            if ($driver != null) {
                $driver->vehicle_id = null;
                $driver->save();
            }

        }
        DriverVehicleModel::where('vehicle_id', $request->id)->delete();

        VehicleModel::find($request->get('id'))->income()->delete();
        VehicleModel::find($request->get('id'))->expense()->delete();
        VehicleModel::find($request->get('id'))->delete();
        VehicleReviewModel::where('vehicle_id', $request->get('id'))->delete();

        ServiceReminderModel::where('vehicle_id', $request->get('id'))->delete();
        FuelModel::where('vehicle_id', $request->get('id'))->delete();
        return redirect()->route('vehicles.index');
    }

    public function edit($id)
    {
        // dd($id);
        $assigned = DriverVehicleModel::get();
        $did[] = 0;

        foreach ($assigned as $d) {
            $did[] = $d->driver_id;
        }

        $data = DriverVehicleModel::where('vehicle_id', $id)->first();
        // $except = array_diff($did, array($data->driver_id));
        if ($data != null) {
            $except = array_diff($did, array($data->driver_id));
        } else { $except = $did;}

        $drivers = User::whereUser_type("D")->whereNotIn('id', $except)->get();
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $groups = VehicleGroupModel::all();
        } else {
            $groups = VehicleGroupModel::where('id', Auth::user()->group_id)->get();
        }
        $vehicle = VehicleModel::where('id', $id)->get()->first();
        $udfs = unserialize($vehicle->getMeta('udf'));

        $purchase_info = DB::table('vehicle_purchaseinfo')->where('vehicle_id','=',$id)->first();
        // dd($purchase_info);
        // dd($udfs);
        // foreach ($udfs as $key => $value) {
        //     # code...
        //     echo $key . " - " . $value . "<br>";
        // }
        // dd($vehicle->year);
        $types = VehicleTypeModel::all();
        return view("vehicles.edit", compact('vehicle', 'groups', 'drivers', 'udfs', 'types','purchase_info'));
    }
    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = Str::uuid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);

        $x = VehicleModel::find($id)->update([$field => $fileName1]);

    }

    private function upload_doc($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = Str::uuid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $vehicle = VehicleModel::find($id);
        $vehicle->setMeta([$field => $fileName1]);
        $vehicle->save();

    }

    public function update(VehicleRequest $request)
    {

        $vehicle = $request->get('id');
        if ($request->file('vehicle_image') && $request->file('vehicle_image')->isValid()) {
            $this->upload_file($request->file('vehicle_image'), "vehicle_image", $vehicle);
        }
        if ($request->file('rc_image') && $request->file('rc_image')->isValid()) {
            $this->upload_file($request->file('rc_image'), "rc_image", $vehicle);
        }

        $user = VehicleModel::find($request->get("id"));
        $form_data = $request->all();
        // dd($form_data);
        unset($form_data['vehicle_image']);
        unset($form_data['rc_image']);
        unset($form_data['documents']);
        unset($form_data['udf']);

        $user->update($form_data);

        if ($request->get("in_service")) {
            $user->in_service = 1;
        } else {
            $user->in_service = 0;
        }
        $user->int_mileage = $request->get("int_mileage");
        // $user->lic_exp_date = $request->get('lic_exp_date');
        // $user->reg_exp_date = $request->get('reg_exp_date');
        // $user->udf = serialize($request->get('udf'));
        $hrs = empty($request->hours) ? "00" : $request->hours;
        $mns = empty($request->mins) ? "00" : $request->mins;
        $user->average = $request->average;
        $user->time_average = $hrs.":".$mns;
        $user->owner_name = $request->owner_name;
        $user->owner_number = $request->owner_number;
        $user->rc_number = $request->rc_number;
        $user->save();

        return Redirect::route("vehicles.index");
        // return redirect()->route('vehicles.index');

    }

    public function store(VehicleRequest $request)
    {
        // dd($request->all());
        $user_id = $request->get('user_id');
        $vehicle = VehicleModel::create([
            'make' => $request->get("make"),
            'model' => $request->get("model"),
            // 'type' => $request->get("type"),
            'year' => $request->get("year"),
            'engine_type' => $request->get("engine_type"),
            // 'horse_power' => $request->get("horse_power"),
            'color' => $request->get("color"),
            // 'vin' => $request->get("vin"),
            'license_plate' => $request->get("license_plate"),
            'int_mileage' => $request->get("int_mileage"),
            'group_id' => $request->get('group_id'),
            'user_id' => $request->get('user_id'),
            // 'lic_exp_date' => $request->get('lic_exp_date'),
            // 'reg_exp_date' => $request->get('reg_exp_date'),
            'in_service' => $request->get("in_service"),
            'type_id' => $request->get('type_id'),
            'engine_no' => $request->get('engine_no'),
            'chassis_no' => $request->get('chassis_no'),
            // 'vehicle_image' => $request->get('vehicle_image'),
        ])->id;
        if ($request->file('vehicle_image') && $request->file('vehicle_image')->isValid()) {
            $this->upload_file($request->file('vehicle_image'), "vehicle_image", $vehicle);
        }
        if ($request->file('rc_image') && $request->file('rc_image')->isValid()) {
            $this->upload_file($request->file('rc_image'), "rc_image", $vehicle);
        }

        $meta = VehicleModel::find($vehicle);
        $meta->setMeta([
            'ins_number' => "",
            'ins_exp_date' => "",
            'documents' => "",
        ]);
        // $meta->udf = serialize($request->get('udf'));
        $hrs = empty($request->hours) ? "00" : $request->hours;
        $mns = empty($request->mins) ? "00" : $request->mins;
        $meta->average = $request->average;
        $meta->time_average = $hrs.":".$mns;
        $meta->owner_name = $request->owner_name;
        $meta->owner_number = $request->owner_number;
        $meta->rc_number = $request->rc_number;
        $meta->save();

        $vehicle_id = $vehicle;

        return redirect("admin/vehicles/" . $vehicle_id . "/edit?tab=vehicle");
    }

    public function store_insurance(InsuranceRequest $request)
    {
        // dd($request->all());
        // $this->validate($request,[
        //     'insurance_number'
        // ]);
        $vehicle = VehicleModel::find($request->get('vehicle_id'));
        $vehicle->setMeta([
            'ins_number' => $request->get("insurance_number"),
            'ins_exp_date' => $request->get('exp_date'),
            'ins_renew_duration' => $request->get('ins_renew_duration'),
            'ins_renew_amount' => $request->get('ins_renew_amount'),
            'fitness_tax' => $request->get('fitness_tax'),
            'fitness_expdate' => $request->get('fitness_expdate'),
            'fitness_renew_duration' => $request->get('fitness_renew_duration'),
            'fitness_renew_amount' => $request->get('fitness_renew_amount'),
            'road_tax' => $request->get('road_tax'),
            'road_expdate' => $request->get('road_expdate'),
            'roadtax_renew_duration' => $request->get('roadtax_renew_duration'),
            'roadtax_renew_amount' => $request->get('roadtax_renew_amount'),
            'permit_number' => $request->get('permit_number'),
            'permit_expdate' => $request->get('permit_expdate'),
            'permit_renew_duration' => $request->get('permit_renew_duration'),
            'permit_renew_amount' => $request->get('permit_renew_amount'),
            'pollution_tax' => $request->get('pollution_tax'),
            'pollution_expdate' => $request->get('pollution_expdate'),
            'pollution_renew_duration' => $request->get('pollution_renew_duration'),
            'pollution_renew_amount' => $request->get('pollution_renew_amount'),
            'fast_tag' => $request->get('fast_tag'),
            'gps_number' => $request->get('gps_number'),
            // 'documents' => $request->get('documents'),
        ]);
        $vehicle->save();
        
        // Insurance Documents
        if ($request->file('documents') && $request->file('documents')->isValid()) {
            $this->upload_doc($request->file('documents'), 'documents', $vehicle->id);
        }

        // Fitness Documents
        if ($request->file('fitness_taxdocs') && $request->file('fitness_taxdocs')->isValid()) {
            $this->upload_doc($request->file('fitness_taxdocs'), 'fitness_taxdocs', $vehicle->id);
        }

        // Road Tax Documents
        if ($request->file('road_docs') && $request->file('road_docs')->isValid()) {
            $this->upload_doc($request->file('road_docs'), 'road_docs', $vehicle->id);
        }

        // Permit Documents
        if ($request->file('permit_docs') && $request->file('permit_docs')->isValid()) {
            $this->upload_doc($request->file('permit_docs'), 'permit_docs', $vehicle->id);
        }

        // Pollution Documents
        if ($request->file('pollution_docs') && $request->file('pollution_docs')->isValid()) {
            $this->upload_doc($request->file('pollution_docs'), 'pollution_docs', $vehicle->id);
        }

        // Pollution Documents
        if ($request->file('fasttag_docs') && $request->file('fasttag_docs')->isValid()) {
            $this->upload_doc($request->file('fasttag_docs'), 'fasttag_docs', $vehicle->id);
        }

        // return $vehicle;
        return redirect('admin/vehicles/' . $request->get('vehicle_id') . '/edit?tab=insurance');
    }

    public function view_event($id)
    {

        $data['vehicle'] = VehicleModel::where('id', $id)->get()->first();
        $data['purch_info'] =  DB::table('vehicle_purchaseinfo')->where('vehicle_id',$id)->first();
        // dd($data);
        return view("vehicles.view_event", $data);
    }

    public function assign_driver(Request $request)
    {
        $records = User::meta()->where('users_meta.key', '=', 'vehicle_id')->where('users_meta.value', '=', $request->get('vehicle_id'))->get();
        // remove records of this vehicle which are assigned to other drivers
        foreach ($records as $record) {
            $record->vehicle_id = null;
            $record->save();
        }
        $vehicle = VehicleModel::find($request->get('vehicle_id'));
        //dd($vehicle);
        if($request->get('driver_id') != ''){
            $vehicle->driver_id = $request->get('driver_id');
            $vehicle->save();
        } 
        //echo $request->get('driver_id'); 
        if($request->get('driver_id') != ''){  
           // echo 'hi'; die();
            DriverVehicleModel::updateOrCreate(['vehicle_id' => $request->get('vehicle_id')], ['vehicle_id' => $request->get('vehicle_id'), 'driver_id' => $request->get('driver_id')]);
            DriverLogsModel::create(['driver_id' => $request->get('driver_id'), 'vehicle_id' => $request->get('vehicle_id'), 'date' => date('Y-m-d H:i:s')]);
            $driver = User::find($request->get('driver_id'));
            if ($driver != null) {
                $driver->vehicle_id = $request->get('vehicle_id');
                $driver->save();
            }
            return redirect('admin/vehicles/' . $request->get('vehicle_id') . '/edit?tab=driver');
       
       
        }
        else{
           // echo 'else'; die();
            DriverVehicleModel::where('vehicle_id' , $request->get('vehicle_id'))->delete();
            $driver = User::find($request->get('driver_id'));
            if ($driver != null) {
                $driver->vehicle_id = $request->get('vehicle_id');
                $driver->save();
            }
            return redirect('admin/vehicles/' . $request->get('vehicle_id') . '/edit?tab=driver');
       
        }

    }

    public function vehicle_review()
    {
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $data['vehicles'] = VehicleModel::get();
        } else {
            $data['vehicles'] = VehicleModel::where('group_id', $user->group_id)->get();
        }
        return view('vehicles.vehicle_review', $data);
    }

    public function store_vehicle_review(VehiclReviewRequest $request)
    {
        // dd($request->all());
        $petrol_card = array('flag' => $request->get('petrol_card'), 'text' => $request->get('petrol_card_text'));
        $lights = array('flag' => $request->get('lights'), 'text' => $request->get('lights_text'));
        $invertor = array('flag' => $request->get('invertor'), 'text' => $request->get('invertor_text'));
        $car_mats = array('flag' => $request->get('car_mats'), 'text' => $request->get('car_mats_text'));
        $int_damage = array('flag' => $request->get('int_damage'), 'text' => $request->get('int_damage_text'));
        $int_lights = array('flag' => $request->get('int_lights'), 'text' => $request->get('int_lights_text'));
        $ext_car = array('flag' => $request->get('ext_car'), 'text' => $request->get('ext_car_text'));
        $tyre = array('flag' => $request->get('tyre'), 'text' => $request->get('tyre_text'));
        $ladder = array('flag' => $request->get('ladder'), 'text' => $request->get('ladder_text'));
        $leed = array('flag' => $request->get('leed'), 'text' => $request->get('leed_text'));
        $power_tool = array('flag' => $request->get('power_tool'), 'text' => $request->get('power_tool_text'));
        $ac = array('flag' => $request->get('ac'), 'text' => $request->get('ac_text'));
        $head_light = array('flag' => $request->get('head_light'), 'text' => $request->get('head_light_text'));
        $lock = array('flag' => $request->get('lock'), 'text' => $request->get('lock_text'));
        $windows = array('flag' => $request->get('windows'), 'text' => $request->get('windows_text'));
        $condition = array('flag' => $request->get('condition'), 'text' => $request->get('condition_text'));
        $oil_chk = array('flag' => $request->get('oil_chk'), 'text' => $request->get('oil_chk_text'));
        $suspension = array('flag' => $request->get('suspension'), 'text' => $request->get('suspension_text'));
        $tool_box = array('flag' => $request->get('tool_box'), 'text' => $request->get('tool_box_text'));

        VehicleReviewModel::create([
            'user_id' => $request->get('user_id'),
            'vehicle_id' => $request->get('vehicle_id'),
            'reg_no' => $request->get('reg_no'),
            'kms_outgoing' => $request->get('kms_out'),
            'kms_incoming' => $request->get('kms_in'),
            'fuel_level_out' => $request->get('fuel_out'),
            'fuel_level_in' => $request->get('fuel_in'),
            'datetime_outgoing' => $request->get('datetime_out'),
            'datetime_incoming' => $request->get('datetime_in'),
            'petrol_card' => serialize($petrol_card),
            'lights' => serialize($lights),
            'invertor' => serialize($invertor),
            'car_mats' => serialize($car_mats),
            'int_damage' => serialize($int_damage),
            'int_lights' => serialize($int_lights),
            'ext_car' => serialize($ext_car),
            'tyre' => serialize($tyre),
            'ladder' => serialize($ladder),
            'leed' => serialize($leed),
            'power_tool' => serialize($power_tool),
            'ac' => serialize($ac),
            'head_light' => serialize($head_light),
            'lock' => serialize($lock),
            'windows' => serialize($windows),
            'condition' => serialize($condition),
            'oil_chk' => serialize($oil_chk),
            'suspension' => serialize($suspension),
            'tool_box' => serialize($tool_box),
        ]);

        return redirect()->route('vehicle_reviews');
    }

    public function vehicle_review_index()
    {
        $data['reviews'] = VehicleReviewModel::orderBy('id', 'desc')->get();
        return view('vehicles.vehicle_review_index', $data);
    }

    public function review_edit($id)
    {
        // dd($id);
        $data['review'] = VehicleReviewModel::find($id);
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $data['vehicles'] = VehicleModel::get();
        } else {
            $data['vehicles'] = VehicleModel::where('group_id', $user->group_id)->get();
        }
        return view('vehicles.vehicle_review_edit', $data);
    }

    public function update_vehicle_review(VehiclReviewRequest $request)
    {
        // dd($request->all());
        $petrol_card = array('flag' => $request->get('petrol_card'), 'text' => $request->get('petrol_card_text'));
        $lights = array('flag' => $request->get('lights'), 'text' => $request->get('lights_text'));
        $invertor = array('flag' => $request->get('invertor'), 'text' => $request->get('invertor_text'));
        $car_mats = array('flag' => $request->get('car_mats'), 'text' => $request->get('car_mats_text'));
        $int_damage = array('flag' => $request->get('int_damage'), 'text' => $request->get('int_damage_text'));
        $int_lights = array('flag' => $request->get('int_lights'), 'text' => $request->get('int_lights_text'));
        $ext_car = array('flag' => $request->get('ext_car'), 'text' => $request->get('ext_car_text'));
        $tyre = array('flag' => $request->get('tyre'), 'text' => $request->get('tyre_text'));
        $ladder = array('flag' => $request->get('ladder'), 'text' => $request->get('ladder_text'));
        $leed = array('flag' => $request->get('leed'), 'text' => $request->get('leed_text'));
        $power_tool = array('flag' => $request->get('power_tool'), 'text' => $request->get('power_tool_text'));
        $ac = array('flag' => $request->get('ac'), 'text' => $request->get('ac_text'));
        $head_light = array('flag' => $request->get('head_light'), 'text' => $request->get('head_light_text'));
        $lock = array('flag' => $request->get('lock'), 'text' => $request->get('lock_text'));
        $windows = array('flag' => $request->get('windows'), 'text' => $request->get('windows_text'));
        $condition = array('flag' => $request->get('condition'), 'text' => $request->get('condition_text'));
        $oil_chk = array('flag' => $request->get('oil_chk'), 'text' => $request->get('oil_chk_text'));
        $suspension = array('flag' => $request->get('suspension'), 'text' => $request->get('suspension_text'));
        $tool_box = array('flag' => $request->get('tool_box'), 'text' => $request->get('tool_box_text'));

        $review = VehicleReviewModel::find($request->get('id'));
        $review->user_id = $request->get('user_id');
        $review->vehicle_id = $request->get('vehicle_id');
        $review->reg_no = $request->get('reg_no');
        $review->kms_outgoing = $request->get('kms_out');
        $review->kms_incoming = $request->get('kms_in');
        $review->fuel_level_out = $request->get('fuel_out');
        $review->fuel_level_in = $request->get('fuel_in');
        $review->datetime_outgoing = $request->get('datetime_out');
        $review->datetime_incoming = $request->get('datetime_in');
        $review->petrol_card = serialize($petrol_card);
        $review->lights = serialize($lights);
        $review->invertor = serialize($invertor);
        $review->car_mats = serialize($car_mats);
        $review->int_damage = serialize($int_damage);
        $review->int_lights = serialize($int_lights);
        $review->ext_car = serialize($ext_car);
        $review->tyre = serialize($tyre);
        $review->ladder = serialize($ladder);
        $review->leed = serialize($leed);
        $review->power_tool = serialize($power_tool);
        $review->ac = serialize($ac);
        $review->head_light = serialize($head_light);
        $review->lock = serialize($lock);
        $review->windows = serialize($windows);
        $review->condition = serialize($condition);
        $review->oil_chk = serialize($oil_chk);
        $review->suspension = serialize($suspension);
        $review->tool_box = serialize($tool_box);
        $review->save();

        return redirect()->route('vehicle_reviews');
    }

    public function destroy_vehicle_review(Request $request)
    {
        VehicleReviewModel::find($request->get('id'))->delete();
        return redirect()->route('vehicle_reviews');
    }

    public function view_vehicle_review($id)
    {
        $data['review'] = VehicleReviewModel::find($id);
        return view('vehicles.view_vehicle_review', $data);

    }

    public function print_vehicle_review($id)
    {
        $data['review'] = VehicleReviewModel::find($id);
        return view('vehicles.print_vehicle_review', $data);
    }

    public function bulk_delete(Request $request)
    {
        $vehicles = VehicleModel::whereIn('id', $request->ids)->get();
        foreach ($vehicles as $vehicle) {
            if ($vehicle->driver_id) {
                $driver = User::find($vehicle->driver_id);
                if ($driver != null) {
                    $driver->vehicle_id = null;
                    $driver->save();
                }
            }
        }

        DriverVehicleModel::whereIn('vehicle_id', $request->ids)->delete();
        VehicleModel::whereIn('id', $request->ids)->delete();
        IncomeModel::whereIn('vehicle_id', $request->ids)->delete();
        Expense::whereIn('vehicle_id', $request->ids)->delete();
        VehicleReviewModel::whereIn('vehicle_id', $request->ids)->delete();
        ServiceReminderModel::whereIn('vehicle_id', $request->ids)->delete();
        FuelModel::whereIn('vehicle_id', $request->ids)->delete();
        return back();
    }

    public function bulk_delete_reviews(Request $request)
    {
        VehicleReviewModel::whereIn('id', $request->ids)->delete();
        return back();
    }

    public function vehicle_tax($id){
        // dd(VehicleModel::find($id));
        $vehicle = VehicleModel::find($id)->get();
        return view('vehicles.vehicle_tax',compact('vehicle'));
    }

    public function vehicle_tax_update(Request $request,$id){
        dd($id);
    }

    public function updateVehicleInfo(Request $request){
        // dd($request->toArray());
        
        // Validation
        $this->validate($request,[
            'purchase_date' => 'required',
            'vehicle_cost' => 'required',
            'amount_paid' => 'required',
            'loan_date' => 'required',
            'loan_account' => 'required',
            'loan_amount' => 'required',
            'bank_name' => 'required',
            'emi_amount' => 'required',
            'emi_date' => 'required',
            'loan_duration' => 'required',
            'gst_amount' => 'required'
        ]);

        $data_arr = [
            'vehicle_id' => $request->vehicle_id,
            'user_id' => $request->user_id,
            'purchase_date' => $request->purchase_date,
            'vehicle_cost' => $request->vehicle_cost,
            'amount_paid' => $request->amount_paid,
            'loan_date' => $request->loan_date,
            'loan_account' => $request->loan_account,
            'loan_amount' => $request->loan_amount,
            'bank_name' => $request->bank_name,
            'emi_amount' => $request->emi_amount,
            'emi_date' => $request->emi_date,
            'loan_duration' => $request->loan_duration,
            'gst_amount' => $request->gst_amount
        ];

        // dd($data_arr);
        if(DB::table('vehicle_purchaseinfo')->where('vehicle_id',$request->vehicle_id)->exists()){
            // echo "Exists";
            DB::table('vehicle_purchaseinfo')->where('vehicle_id',$request->vehicle_id)->update($data_arr);
        }else{
            // echo "Not Exists";
            DB::table('vehicle_purchaseinfo')->insert($data_arr);
        }
        // exit;
        return redirect()->back();
    }

    public function report(){
        $data['vehicles'] = VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name','id');
		$data['request'] = null;
		// dd($data);
		return view('vehicles.report', $data);
    }

    public function report_post(Request $request){
        // dd($request->all());
        $data['vehicles'] = VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name','id');
        // $data['bookings'] = Bookings::select(DB::raw("count(id) a  totalbooking,sum()"));
        if($request->get('date1')==null)
			$start = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup','ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup','DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			// dd($start,$end);

        // Bookings
        $vehicle_id = $request->get('vehicle_id');
		$bookings = Bookings::where('vehicle_id',$vehicle_id)->whereBetween('pickup',[$start,$end])->get();
		
        // dd($bookings);
        foreach($bookings as $b){
            $book['kms'][] = explode(" ",$b->getMeta('distance'))[0];
            $book['fuel'][] = $b->getMeta('pet_required');
            $book['price'][] = $b->getMeta('total_price');
        }
        $book['totalbooking'] = $bookings->count();
        $book['totalkms'] = isset($book['kms']) && count($book['kms'])>0 ? array_sum($book['kms']) : 0.00;
        $book['totalfuel'] = isset($book['fuel']) && count($book['fuel'])>0 ? array_sum($book['fuel']) : 0.00;
        $book['totalprice'] = isset($book['price']) && count($book['price'])>0 ? array_sum($book['price']) : 0.00;
        
        //Fuel
        $fuelModel = FuelModel::where('vehicle_id',$vehicle_id)->whereBetween('date',[$start,$end])->get();
        $fuelArray = array();
        foreach($fuelModel as $f){
            // if(in_array($f->fuel_type,$fuelArray)){
                $fuelName = FuelType::find($f->fuel_type)->fuel_name;
                $fuelArray[$fuelName]['id'][] = $f->id;
                $fuelArray[$fuelName]['ltr'][] = $f->qty;
                // $fuelArray[$fuelName]['cost'][] = $f->cost_per_unit;
                $fuelArray[$fuelName]['total'][] = $f->qty * $f->cost_per_unit;
        }

        // Advance
        $advanceBookings = Bookings::where('vehicle_id',$vehicle_id)->whereBetween('pickup', [$start, $end])->meta()->where(function($query){
            $query->where('bookings_meta.key', '=', 'advance_pay')
				  ->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
        })->get();
        $advance = array();
       foreach($advanceBookings as $ad){
          $advance['amount'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
          $advance['times'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
          $advance['id'][] = $ad->id;
          //   $advance[] = 
        }
        // dd($advance["id"]);
        $advance['times'] = isset($advance['times']) ? $advance['times'] : 0;
        $advance['id'] = isset($advance['id']) ? $advance['id'] : [];
        $advance['details'] = AdvanceDriver::select('id','param_id',DB::raw('count(value) as times'),DB::raw('SUM(value) as amount'))->whereIn('booking_id',$advance['id'])->orderBy('param_id')->groupBy('param_id')->get();
        foreach($advance['details'] as $det){
            $det->label = $det->param_name->label;
        }
        $workorders = WorkOrders::where('vehicle_id',$vehicle_id)->whereBetween('required_by',[$start,$end])->get();
        // dd($start,$end,$workorders);
        $prepArray = array();
        foreach($workorders as $wo){
            $prepArray['gtotal'][] = empty($wo->grand_total) ? $wo->price : $wo->grand_total;
            $prepArray['cgst'][] = empty($wo->cgst) ? $wo->cgst : $wo->cgst;
            $prepArray['sgst'][] = empty($wo->sgst) ? $wo->sgst : $wo->sgst;
            $prepArray['vendors'][] = $wo->vendor_id;
            $prepArray['status'][$wo->status][] = $wo->status;
            $prepArray['id'][] = $wo->id;

        }
        $data['wo'] = Helper::toCollection([
            'count'=>isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? count($prepArray['gtotal']) : 0,
            'grand_total'=>isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? array_sum($prepArray['gtotal']) : 0,
            'cgst'=>isset($prepArray['cgst']) && !empty($prepArray['cgst']) ? array_sum($prepArray['cgst']) : 0,
            'sgst'=>isset($prepArray['sgst']) && !empty($prepArray['sgst']) ? array_sum($prepArray['sgst']) : 0,
            'vendors'=>isset($prepArray['vendors']) && !empty($prepArray['vendors']) ? count(array_unique($prepArray['sgst'])) : 0,
            'status' =>isset($prepArray['status']) && count($prepArray['status'])>0 ? $prepArray['status'] : []
        ]);
        // Work order/Part
        // dd($data['workorders']);
        // dd($prepArray);
        $workOrderIds = isset($prepArray['id']) && count($prepArray['id'])>0 ? $prepArray['id'] : [];
        $data['partsUsed'] = PartsUsedModel::select('part_id',DB::raw('SUM(total) as total'),DB::raw('SUM(qty) as qty'))->whereIn('work_id',$workOrderIds)->groupBy('part_id')->get();
        //Work order/Part Ends
        // dd($data);
        // foreach($data['partsUsed'] as $pd){
        //     dd($pd->part_id);
        //     empty($pd->part_id) ?? dd(123);
        //     // dd(PartsModel::find($pd->part_id)->title);
        //     $pd->parts_name = PartsModel::find($pd->part_id)->title;
        // }
        // Tranactions
		// $data['fuel'] = $fuelFinal;
		$data['request'] = $request->all();
        $data['vehicle'] = VehicleModel::where('id',$request->vehicle_id)->first();
        $data['from_date'] = $start;
        $data['to_date'] = $end;
        $data['book'] = Helper::toCollection($book);
		$data['fuels'] = Helper::toCollection($fuelArray);
        $data['advances'] = Helper::toCollection($advance);
        $data['result'] = "";
        // dd($data);
        return view('vehicles.report', $data);
    }

    public function report_print(Request $request){
        $data['vehicles'] = VehicleModel::select("id",DB::raw("CONCAT(make,'-',model,'-',license_plate) as name"))->pluck('name','id');
        // $data['bookings'] = Bookings::select(DB::raw("count(id) a  totalbooking,sum()"));
        if($request->get('date1')==null)
			$start = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup','ASC')->take(1)->first('pickup')->pickup;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Bookings::select(DB::raw('DATE(pickup) as pickup'))->orderBy('pickup','DESC')->take(1)->first('pickup')->pickup;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));
			// dd($start,$end);

        // Bookings
        $vehicle_id = $request->get('vehicle_id');
		$bookings = Bookings::where('vehicle_id',$vehicle_id)->whereBetween('pickup',[$start,$end])->get();
		
        // dd($bookings);
        foreach($bookings as $b){
            $book['kms'][] = explode(" ",$b->getMeta('distance'))[0];
            $book['fuel'][] = $b->getMeta('pet_required');
            $book['price'][] = $b->getMeta('total_price');
        }
        $book['totalbooking'] = $bookings->count();
        $book['totalkms'] = isset($book['kms']) && count($book['kms'])>0 ? array_sum($book['kms']) : 0.00;
        $book['totalfuel'] = isset($book['fuel']) && count($book['fuel'])>0 ? array_sum($book['fuel']) : 0.00;
        $book['totalprice'] = isset($book['price']) && count($book['price'])>0 ? array_sum($book['price']) : 0.00;
        
        //Fuel
        $fuelModel = FuelModel::where('vehicle_id',$vehicle_id)->whereBetween('date',[$start,$end])->get();
        $fuelArray = array();
        foreach($fuelModel as $f){
            // if(in_array($f->fuel_type,$fuelArray)){
                $fuelName = FuelType::find($f->fuel_type)->fuel_name;
                $fuelArray[$fuelName]['id'][] = $f->id;
                $fuelArray[$fuelName]['ltr'][] = $f->qty;
                // $fuelArray[$fuelName]['cost'][] = $f->cost_per_unit;
                $fuelArray[$fuelName]['total'][] = $f->qty * $f->cost_per_unit;
        }

        // Advance
        $advanceBookings = Bookings::where('vehicle_id',$vehicle_id)->whereBetween('pickup', [$start, $end])->meta()->where(function($query){
            $query->where('bookings_meta.key', '=', 'advance_pay')
				  ->whereRaw('bookings_meta.value IS NOT NULL AND bookings_meta.value!=0');
        })->get();
        $advance = array();
       foreach($advanceBookings as $ad){
          $advance['amount'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
          $advance['times'][] = !empty($ad->getMeta('advance_pay')) ? $ad->getMeta('advance_pay') : 0;
          $advance['id'][] = $ad->id;
          //   $advance[] = 
        }
        // dd($advance["id"]);
        $advance['times'] = isset($advance['times']) ? $advance['times'] : 0;
        $advance['id'] = isset($advance['id']) ? $advance['id'] : [];
        $advance['details'] = AdvanceDriver::select('id','param_id',DB::raw('count(value) as times'),DB::raw('SUM(value) as amount'))->whereIn('booking_id',$advance['id'])->orderBy('param_id')->groupBy('param_id')->get();
        foreach($advance['details'] as $det){
            $det->label = $det->param_name->label;
        }
        $workorders = WorkOrders::where('vehicle_id',$vehicle_id)->whereBetween('required_by',[$start,$end])->get();
        // dd($start,$end,$workorders);
        $prepArray = array();
        foreach($workorders as $wo){
            $prepArray['gtotal'][] = empty($wo->grand_total) ? $wo->price : $wo->grand_total;
            $prepArray['cgst'][] = empty($wo->cgst) ? $wo->cgst : $wo->cgst;
            $prepArray['sgst'][] = empty($wo->sgst) ? $wo->sgst : $wo->sgst;
            $prepArray['vendors'][] = $wo->vendor_id;
            $prepArray['status'][$wo->status][] = $wo->status;
            $prepArray['id'][] = $wo->id;

        }
        $data['wo'] = Helper::toCollection([
            'count'=>isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? count($prepArray['gtotal']) : 0,
            'grand_total'=>isset($prepArray['gtotal']) && !empty($prepArray['gtotal']) ? array_sum($prepArray['gtotal']) : 0,
            'cgst'=>isset($prepArray['cgst']) && !empty($prepArray['cgst']) ? array_sum($prepArray['cgst']) : 0,
            'sgst'=>isset($prepArray['sgst']) && !empty($prepArray['sgst']) ? array_sum($prepArray['sgst']) : 0,
            'vendors'=>isset($prepArray['vendors']) && !empty($prepArray['vendors']) ? count(array_unique($prepArray['sgst'])) : 0,
            'status' =>isset($prepArray['status']) && count($prepArray['status'])>0 ? $prepArray['status'] : []
        ]);
        // Work order/Part
        // dd($data['workorders']);
        // dd($prepArray);
        $workOrderIds = isset($prepArray['id']) && count($prepArray['id'])>0 ? $prepArray['id'] : [];
        $data['partsUsed'] = PartsUsedModel::select('part_id',DB::raw('SUM(total) as total'),DB::raw('SUM(qty) as qty'))->whereIn('work_id',$workOrderIds)->groupBy('part_id')->get();
        //Work order/Part Ends
        // dd($data);
        // foreach($data['partsUsed'] as $pd){
        //     dd($pd->part_id);
        //     empty($pd->part_id) ?? dd(123);
        //     // dd(PartsModel::find($pd->part_id)->title);
        //     $pd->parts_name = PartsModel::find($pd->part_id)->title;
        // }
        // Tranactions
		// $data['fuel'] = $fuelFinal;
		$data['request'] = $request->all();
        $data['vehicle'] = VehicleModel::where('id',$request->vehicle_id)->first();
        $data['from_date'] = $start;
        $data['to_date'] = $end;
        $data['book'] = Helper::toCollection($book);
		$data['fuels'] = Helper::toCollection($fuelArray);
        $data['advances'] = Helper::toCollection($advance);
        $data['result'] = "";
        // dd($data);
        return view('vehicles.print_report', $data);
    }

}
