<?php

namespace App\Exports;

use App\Model\FuelModel;
use App\Helpers\Helper;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpFoundation\Request;

class FuelExport implements FromView
{
    protected $request;
    public function __construct($req)
    {
        $this->request = $req;
    }
    public function view(): View
    {
        $vendor = $this->request->vendor;
        $fuel_type = $this->request->fuel_type;
        $from_date = !empty($this->request->from_date) ? Helper::ymd($this->request->from_date) : null;
        $to_date = !empty($this->request->to_date) ? Helper::ymd($this->request->to_date) : null;
        $by = $this->request->by;
        $order = $this->request->order;

        $from_date = empty($from_date) ? FuelModel::orderBy('date', 'ASC')->take(1)->first('date')->date : $from_date;
        $to_date = empty($to_date) ? FuelModel::orderBy('date', 'DESC')->take(1)->first('date')->date : $to_date;

        if (empty($vendor) && empty($fuel_type))
            $fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date]);
        elseif (empty($vendor))
            $fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('fuel_type', $fuel_type);
        elseif (empty($fuel_type))
            $fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor);
        else
            $fuelModel = FuelModel::whereBetween('date', [$from_date, $to_date])->where('vendor_name', $vendor)->where('fuel_type', $fuel_type);

        if ($by == "vehicle")
            $index['fuel'] = $fuelAll = $fuelModel->join('vehicles', 'vehicles.id', 'fuel.vehicle_id')->orderBy('vehicles.license_plate', $order)->orderBy('date', $order)->get();
        else
            $index['fuel'] = $fuelAll = $fuelModel->orderBy('date', $order)->get();

        // $index['fuelTotal'] = $fuelModel->select(DB::raw('SUM(qty) as qty'),DB::raw('AVG(cost_per_unit) as per_unit'),DB::raw('SUM(qty) * AVG(cost_per_unit) as amount'))->first();
        foreach ($index['fuel'] as $sum) {
            $summation[] = $sum->qty * $sum->cost_per_unit;
        }
        $index['fuelTotal'] = array_sum($summation);


        // $index['fuelLtr'] = $fuelAll->whereIn('fuel_type', Helper::fuelPackageData('ltr'))->sum('qty');
        // $index['fuelPc'] = $fuelAll->whereIn('fuel_type', Helper::fuelPackageData('pc'))->sum('qty');
        $index['fuelQtySum'] = $fuelAll->sum('qty');
        return view('exports.fuel', $index);
    }
    // public function headings(): array
    // {
    //     return [
    //         'SL#',
    //         'Date',
    //         'Vendor',
    //         'Vehicle',
    //         'Fuel Type',
    //         'Rate',
    //         'Quantity',
    //         'Amount',
    //     ];
    // }
}
