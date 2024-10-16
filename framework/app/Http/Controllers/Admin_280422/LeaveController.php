<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Leave;
use App\Model\User;
use App\Model\DriverVehicleModel;
use App\Model\Payroll;
use Auth;

class LeaveController extends Controller
{

    public function index()
    {

        $index['data'] = Leave::orderBy('id', 'DESC')->get();
        // dd($index);
        return view('leaves.index', $index);
    }

    public function create()
    {
        $index['data'] = User::where('user_type', 'D')->pluck('name', 'id');
        // dd($index);
        return view('leaves.create', $index);
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        $users = User::where('user_type', 'D')->get('id');
        $date = !empty($request->date) ? date("Y-m-d", strtotime($request->date)) : null;
        if ($request->driver_id == null)
            $request->merge(['driver_id' => []]);

        // dd($request->all());
        foreach ($users as $user) {
            if (in_array($user->id, $request->driver_id) && $request->present_type == 2) {
                // dd($user->id);
                // dd(111);
                $ispresent = $request->halfday[$user->id]; //absent
                $remarks = $request->remarks[$user->id];
            } else {
                $ispresent = 1; //present
                $remarks = '';
            }

            // $arr[$user->id] = ['driver_id'=>$user->id,'date'=>date('Y-m-d'),'is_present'=>$ispresent,'remarks'=>$remarks];
            if (Leave::where('driver_id', $user->id)->where('date', $date)->exists()) {
                Leave::where('driver_id', $user->id)->where('date', $date)->delete();
            }
            Leave::create(['driver_id' => $user->id, 'date' => $date, 'is_present' => $ispresent, 'remarks' => $remarks]);
        }
        // dd($arr);
        return redirect()->route('leave.index');
    }

    public function edit(Leave $leave)
    {
        // dd($leave);
        $index['drivers'] =  User::where('user_type', 'D')->pluck('name', 'id');
        $index['leave'] = $leave;
        $index['isSalaryPaid'] = Payroll::where(['user_id' => $leave->driver_id, 'for_date' => date("Y-m-01", strtotime($leave->date))])->exists() ? true : false;
        // dd($index);
        return view('leaves.edit', $index);
    }

    public function update(Leave $leave, Request $request)
    {
        //Code
        // dd($request->all());
        // dd($leave);

        $leave->is_present = $request->is_present;
        $leave->remarks = $request->remarks;
        $leave->save();
        return redirect()->back();
    }

    public function destroy(Leave $leave)
    {
        //Code
        $leave->delete();
        return redirect()->back();
    }

    public function get_remarks($ids)
    {
        // dd($ids);
        $drivers = explode(",", $ids);
        $users =  User::where('user_type', 'D')->whereIn('id', $drivers)->get();
        // dd($users->toArray());
        return view('leaves.remarks', compact('users'));
    }

    public function view_event(Request $request)
    {
        // dd($request->id);
        $index['leave'] = $leave = Leave::find($request->id);
        $like = date('Y-m', strtotime($leave->date));
        // dd($like);
        $index['historys'] = Leave::where('date', 'LIKE', "%$like%")->orderBy('date', 'ASC')->where('driver_id', $leave->driver_id)->get();
        // dd($index);
        return view('leaves.view_event', $index);
    }
}
