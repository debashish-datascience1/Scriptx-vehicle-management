<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Leave;
use App\Model\User;
use App\Model\DriverVehicleModel;
use App\Model\Manufacturer;
use Auth;

class ManufacturerController extends Controller {

    public function index()
    {
        $index['data'] = Manufacturer::orderBy('id','DESC')->get();
        // dd(Manufacturer::get());
        return view('manufacturer.index',$index);
    }

    public function create(){
        return view('manufacturer.create');
    }

    public function store(Request $request){
        // dd($request->toArray());
        $form_data = $request->all();
        unset($form_data['_token']);
        Manufacturer::create($form_data);
        return redirect()->route('manufacturer.index');
    }

    public function edit(Manufacturer $manufacturer){
        // dd($leave);
        $index['manufacturer'] = $manufacturer;
        return view('manufacturer.edit',$index);
    }

    public function update(Manufacturer $manufacturer,Request $request){
        //Code
        // dd($request->all());
        
        $manufacturer->name = $request->name;
        $manufacturer->save();
        return redirect()->back();
    }

    public function destroy(Manufacturer $manufacturer){
        //Code
       $manufacturer->delete();
       return redirect()->back();
    }

    public function get_remarks($ids){
        // dd($ids);
        $drivers = explode(",", $ids);
        $users =  User::where('user_type','D')->whereIn('id',$drivers)->get();
        // dd($users->toArray());
        return view('leaves.remarks',compact('users'));
    }

    public function view_event(Request $request){
        // dd($request->id);
        $index['leave'] = $leave = Leave::find($request->id);
        $like = date('Y-m',strtotime($leave->date));
        // dd($like);
        $index['historys'] = Leave::where('date','LIKE',"%$like%")->orderBy('date','ASC')->where('driver_id',$leave->driver_id)->get();
        // dd($index);
        return view('leaves.view_event',$index);
    }

    public function report(){
        $data['drivers'] = User::where('user_type','D')->pluck('name','id');
        $data['request'] = null;
        // dd($data);
        return view('leaves.report',$data);
    }

    public function report_post(Request $request){
        // dd($request->all());
        $driver_id = $request->get('driver_id');
        if($request->get('date1')==null)
			$start = Leave::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Leave::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

        
        if (!empty($driver_id)) {
            $data['leaves'] = Leave::where('driver_id',$driver_id)->whereBetween('date', [$start, $end])->get();
        }
        else {
            $data['leaves'] = Leave::whereBetween('date', [$start, $end])->get();
        }

        $data['result'] = "";
        $data['driver_id'] = $driver_id;
        $data['total_present'] = $data['leaves']->count();
        $data['total_absent'] = $data['leaves']->where('is_present',2)->count();
		$data['request'] = $request->all();
        $data['drivers'] = User::where('user_type','D')->pluck('name','id');
        $data['date1'] = $start;
		$data['date2'] = $end;
        // dd($data);
        return view('leaves.report', $data);
    }

    public function report_print(Request $request){
        // dd($request->all());
        // dd($request->all());
        $driver_id = $request->get('driver_id');
        if($request->get('date1')==null)
			$start = Leave::orderBy('date','asc')->take(1)->first('date')->date;
		else
			$start = date('Y-m-d', strtotime($request->get('date1')));

		if($request->get('date2')==null)
			$end = Leave::orderBy('date','desc')->take(1)->first('date')->date;
		else
			$end = date('Y-m-d', strtotime($request->get('date2')));

        
        if (!empty($driver_id)) {
            $data['leaves'] = Leave::where('driver_id',$driver_id)->whereBetween('date', [$start, $end])->get();
        }
        else {
            $data['leaves'] = Leave::whereBetween('date', [$start, $end])->get();
        }

        $data['result'] = "";
        $data['driver_id'] = $driver_id;
        $data['driver_data'] = User::where('id',$driver_id)->first();
        $data['total_present'] = $data['leaves']->count();
        $data['total_absent'] = $data['leaves']->where('is_present',2)->count();
		$data['request'] = $request->all();
        $data['drivers'] = User::where('user_type','D')->pluck('name','id');
        $data['from_date'] = $start;
		$data['to_date'] = $end;
        // dd($data);
        return view('leaves.print-report', $data);
    }

}