<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Leave;
use App\Model\Payroll;
use App\Model\User;
use Auth;
use DB;
use Helper;

class BulkLeaveController extends Controller
{

    public function index()
    {

        $index['data'] = Leave::orderBy('id', 'DESC')->groupBy('driver_id', DB::raw("YEAR(date)"), DB::raw("MONTH(date)"))->get();
        foreach ($index['data'] as $i) {
            $ym = date('Y-m', strtotime($i->date));
            $i->attendance = Leave::select('date', 'is_present')->where('driver_id', $i->driver_id)->where('date', 'LIKE', "%$ym%")->orderBy('id', 'DESC')->take(7)->get();
            $i->ym = $ym;
            // dd($i);
        }
        // dd($index['data']->first());
        return view('bulk_leave.index', $index);
    }

    public function create()
    {
        $index['data'] = User::where('user_type', 'D')->orderBy('name', 'ASC')->pluck('name', 'id');
        // $index['years'] = Leave::select(DB::raw("YEAR(date) as years"))->groupBy('years')->orderBy('date','ASC')->pluck('years','years');
        // $monthsArray =  Leave::select(DB::raw("MONTH(date) as months"))->groupBy('months')->orderBy('date','ASC')->pluck('months','months');
        // foreach($monthsArray as $k=>$m){
        //     $months[$k] = Helper::getMonth($m,true) ;
        // }
        $index['years'] = Helper::getYears(['from' => 1970, 'to' => date('Y')]);
        $index['months'] = Helper::getMonths();
        $index['select_array'] = [
            'all-present' => "All Present",
            'all-absent' => "All Absent",
            'randomize' => "Randomize",
            'randomize-more' => "Randomize",
        ];


        // dd($index);
        return view('bulk_leave.create', $index);
    }

    public function store(Request $request)
    {
        // dd(12);
        // dd($request->toArray());
        $driver_id = $request->driver_id;
        $years = $request->years;
        $months = $request->months < 10 ? "0" . $request->months : $request->months;
        $date = $years . "-" . $months;
        $dates = $request->dates;
        $attendance = $request->attendance;
        $remarks = $request->remarks;
        $bulk_at = date("Y-m-d H:i:s");

        if (Leave::where('date', 'LIKE', "%$date%")->where('driver_id', $driver_id)->exists()) {
            Leave::where('date', 'LIKE', "%$date%")->where('driver_id', $driver_id)->delete();
        }

        foreach ($dates as $k => $dt) {
            $day = $dt < 10 ? "0" . $dt : $dt;
            $new_date = $date . "-" . $day;
            // dd($new_date);
            $data  = [
                'driver_id' => $driver_id,
                'date' => $new_date,
                'is_present' => $attendance[$k],
                'remarks' => $remarks[$k],
                'bulk_at' => $bulk_at,
            ];
            Leave::create($data);
        }

        return redirect()->route('bulk_leave.index');
    }

    // public function edit_bulk(Request $request){
    //     dd($request->all());
    // }

    public function show(Request $request)
    {
        dd($request->all());
    }

    public function edit(Leave $leave)
    {
        // dd($leave);
        $index['drivers'] =  User::where('user_type', 'D')->pluck('name', 'id');
        $index['leave'] = $leave;
        return view('bulk_leave.edit', $index);
    }

    // public function edit_bulk(array $array){
    //     // dd($leave);
    //     $index['drivers'] =  User::where('user_type','D')->pluck('name','id');
    //     $index['leave'] = $leave;
    //     return view('bulk_leave.edit',$index);
    // }

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
        dd($leave);
        $leave->delete();
        return redirect()->back();
    }

    public function get_remarks($ids)
    {
        // dd($ids);
        $drivers = explode(",", $ids);
        $users =  User::where('user_type', 'D')->whereIn('id', $drivers)->get();
        // dd($users->toArray());
        return view('bulk_leave.remarks', compact('users'));
    }

    public function view_event(Request $request)
    {
        // dd($request->id);
        $index['leave'] = $leave = Leave::find($request->id);
        $like = date('Y-m', strtotime($leave->date));
        // dd($like);
        $attendance = Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id);
        // dd($attendance->where('is_present', '4')->get());
        $present = Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', '1')->count();
        $absent = Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', '2')->count();
        // dd(Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', 1)->get());

        $first_halfdays = Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', '3')->exists() ? Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', '3')->count() : 0;

        $second_halfdays = Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', '4')->exists() ? Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->where('is_present', '4')->count() : 0;

        $index['historys'] = Leave::where('date', 'LIKE', "%$like%")->where('driver_id', $leave->driver_id)->orderBy('date', 'ASC')->get();
        $index['present'] = $present;
        $index['absent'] = $absent;
        $index['first_halfdays'] = $first_halfdays;
        $index['second_halfdays'] = $second_halfdays;
        // dd($index);
        return view('bulk_leave.view_event', $index);
    }

    public function getDatesPage(Request $request)
    {
        // dd($request->all());
        $year = $request->year;
        $month = $request->month < 10 ? "0" . $request->month : $request->month;
        $driver = $request->driver;
        $startDate = $year . "-" . $month . "-01";
        $start = "01";
        $end = date("t", strtotime($startDate));
        $index['days'] = [$start, $end];

        // $nullData = 

        for ($i = (int) $start; $i <= $end; $i++) {
            $day = $i < 10 ? "0" . $i : $i;
            $search = $year . "-" . $month . "-" . $day;
            // $search==31 ?? dd($search);
            // echo $search;
            $leave = Leave::where("date", "LIKE", "%$search%")->where('driver_id', $driver);
            $result = $leave->exists() ? $leave->first() : null;
            // !empty($result) ? $result->date
            $leaveArr[] = $result;
            // $leaveArr1[] = $leave->toSql();
        }
        $index['leaveList'] = $leaveArr;
        $index['isSalaryPaid'] = Payroll::where(['user_id' => $driver, 'for_date' => $startDate])->exists() ? true : false;
        $index['year'] = $year;
        $index['month'] = $month;
        // $index['xyz'] = $leaveArr1;
        // dd($index);
        return view('bulk_leave.getMonths_ajax', $index);
    }
}
