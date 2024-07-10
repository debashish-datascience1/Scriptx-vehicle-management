<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncRequest;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\VehicleModel;
use Auth;
use DB;
use Illuminate\Http\Request;

class Income extends Controller
{

    public function index()
    {
        $data['date1'] = null;
        $data['date2'] = null;
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $data['vehicels'] = VehicleModel::whereIn_service(1)->get();

        } else {
            $data['vehicels'] = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->get();
        }
        $vehicle_ids = $data['vehicels']->pluck('id')->toArray();
        $data['types'] = IncCats::get();
        $income = IncomeModel::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'));
        $data['today'] = $income->get();
        $data['total'] = $income->sum('amount');
        return view("income.index", $data);
    }

    public function store(IncRequest $request)
    {
        IncomeModel::create([
            "vehicle_id" => $request->get("vehicle_id"),
            // "amount" => $request->get("revenue"),
            "amount" => $request->get("tax_total"),
            "user_id" => Auth::id(),
            "date" => $request->get('date'),
            "mileage" => $request->get("mileage"),
            "income_cat" => $request->get("income_type"),
            "tax_percent" => $request->tax_percent,
            "tax_charge_rs" => $request->tax_charge_rs,
        ]);
        $v = VehicleModel::find($request->get("vehicle_id"));

        $v->mileage = $request->get("mileage");
        $v->save();
        return redirect()->route("income.index");
    }

    public function destroy(Request $request)
    {
        IncomeModel::find($request->get('id'))->delete();
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $vehicle_ids = VehicleModel::whereIn_service(1)->pluck('id')->toArray();
        } else {
            $vehicle_ids = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->pluck('id')->toArray();
        }
        $income = IncomeModel::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'));
        $data['today'] = $income->get();
        $data['total'] = $income->sum('amount');
        return view("income.ajax_income", $data);
        // return redirect()->route('income.index');
    }

    public function income_records(Request $request)
    {
        $data['date1'] = $request->date1;
        $data['date2'] = $request->date2;
        $user = Auth::user();
        if ($user->group_id == null || $user->user_type == "S") {
            $data['vehicels'] = VehicleModel::whereIn_service(1)->get();

        } else {
            $data['vehicels'] = VehicleModel::whereIn_service(1)->where('group_id', $user->group_id)->get();
        }
        $vehicle_ids = $data['vehicels']->pluck('id')->toArray();
        $data['types'] = IncCats::get();
        $data['today'] = IncomeModel::whereIn('vehicle_id', $vehicle_ids)->whereBetween('date', [$request->get('date1'), $request->get('date2')])->get();
        $data['total'] = IncomeModel::whereIn('vehicle_id', $vehicle_ids)->whereDate('date', DB::raw('CURDATE()'))->sum('amount');

        return view("income.index", $data);
    }

    public function bulk_delete(Request $request)
    {
        IncomeModel::whereIn('id', $request->ids)->delete();
        return redirect('admin/income');
    }

}
