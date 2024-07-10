<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\VendorRequest;
use App\Model\Vendor;
use App\Model\FuelModel;
use App\Model\FuelType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Importer;
use DB;

class VendorController extends Controller
{

    public function importVendors(ImportRequest $request)
    {
        $file = $request->excel;
        $destinationPath = './assets/samples/'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $file->move($destinationPath, $fileName);

        $excel = Importer::make('Excel');
        $excel->load('assets/samples/' . $fileName);
        $collection = $excel->getCollection()->toArray();
        array_shift($collection);
        // dd($collection);
        foreach ($collection as $vendor) {
            if ($vendor[0] != null) {
                Vendor::create([
                    'name' => $vendor[0],
                    'phone' => $vendor[1],
                    'email' => $vendor[2],
                    'type' => $vendor[3],
                    'website' => $vendor[4],
                    'address1' => $vendor[5],
                    'address2' => $vendor[6],
                    'city' => $vendor[7],
                    'province' => $vendor[8],
                    'postal_code' => $vendor[9],
                    'country' => $vendor[10],
                    'note' => $vendor[11],
                ]);
            }

        }
        return back();
    }

    public function index()
    {
        $index['data'] = Vendor::orderBy('id', 'desc')->get();
        return view('vendors.index', $index);
    }

    public function create()
    {
        $vendor_types = Vendor::orderBy("name")->groupBy("type")->get()->pluck("type")->toArray();
        array_push($vendor_types, "Add New");
        $vendor_types = array_unique($vendor_types);
        return view('vendors.create', compact("vendor_types"));
    }

    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = Str::uuid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);

        $x = Vendor::find($id)->update([$field => $fileName1]);

    }

    public function store(VendorRequest $request)
    {

        $form_data = $request->all();
        unset($form_data['photo']);
        unset($form_data['udf']);
        $id = Vendor::create($form_data)->id;
        $vendor = Vendor::find($id);
        $vendor->udf = serialize($request->get('udf'));
        $vendor->save();
        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $this->upload_file($request->file('photo'), "photo", $id);
        }
        return redirect()->route('vendors.index');

    }

    public function edit($id)
    {
        $index['data'] = Vendor::whereId($id)->first();
        $vendor_types = Vendor::orderBy("name")->groupBy("type")->get()->pluck("type")->toArray();
        array_push($vendor_types, "Machinaries", "Fuel", "Parts");
        $index['vendor_types'] = array_unique($vendor_types);
        $index['udfs'] = unserialize($index['data']->udf);
        return view("vendors.edit", $index);
    }

    public function update(VendorRequest $request)
    {
        $vendor = $request->get('id');
        $vendor = Vendor::find($request->get("id"));
        $vendor->name = $request->get('name');
        $vendor->type = $request->get('type');
        $vendor->website = $request->get('website');
        $vendor->note = $request->get('note');
        $vendor->phone = $request->get('phone');
        $vendor->address1 = $request->get('address1');
        $vendor->address2 = $request->get('address2');
        $vendor->city = $request->get('city');
        $vendor->province = $request->get('province');
        $vendor->email = $request->get('email');
        $vendor->opening_balance = $request->get('opening_balance');
        $vendor->opening_comment = $request->get('opening_comment');
        // $vendor->photo = $request->get('photo');
        $vendor->country = $request->country;
        $vendor->postal_code = $request->postal_code;
        $vendor->udf = serialize($request->get('udf'));
        $vendor->save();
        if ($request->file('photo') && $request->file('photo')->isValid()) {
            $this->upload_file($request->file('photo'), "photo", $vendor->id);
        }
        return redirect()->route('vendors.index');
    }

    public function destroy(Request $request)
    {
        Vendor::find($request->get('id'))->delete();
        return redirect()->route('vendors.index');
    }

    public function view_event(Request $request){
        $index['request'] = $request->id;
        $index['vendor'] = Vendor::find($request->id);
        // dd($index);
        return view('vendors.view_event',$index);
    }

    public function bulk_delete(Request $request)
    {
        Vendor::whereIn('id', $request->ids)->delete();
        return back();
    }

    public function report(){
        // dd("report");
        $index['vendors'] = Vendor::whereIn('type',['Fuel','fuel'])->pluck('name','id');
		$index['fuel_types'] = FuelType::pluck('fuel_name','id');
		$index['request'] = null;
		// dd($index);
		return view('vendors.report',$index);
    }

    public function report_post(Request $request){
        // dd($request->all());
        $vendor = $request->vendor;
        $fuel_type = $request->fuel_type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $from_date = empty($from_date) ? FuelModel::orderBy('date','ASC')->take(1)->first('date')->date : $from_date;
        $to_date = empty($to_date) ? FuelModel::orderBy('date','DESC')->take(1)->first('date')->date : $to_date;

        if(empty($vendor) && empty($fuel_type))
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date]);
        elseif(empty($vendor))
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date])->where('fuel_type',$fuel_type);
        elseif(empty($fuel_type))
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date])->where('vendor_name',$vendor);
        else
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date])->where('vendor_name',$vendor)->where('fuel_type',$fuel_type);

        $index['fuel'] = $fuelModel->orderBy('date','ASC')->get();
        // $index['fuelTotal'] = $fuelModel->select(DB::raw('SUM(qty) as qty'),DB::raw('AVG(cost_per_unit) as per_unit'),DB::raw('SUM(qty) * AVG(cost_per_unit) as amount'))->first();
        foreach($index['fuel'] as $sum){
            $summation[] = $sum->qty * $sum->cost_per_unit;
        }
        $summation = !empty($summation) ? $summation : [0];
        $index['fuelTotal'] = array_sum($summation);
        $index['is_vendor'] = !empty($vendor) ? true : false;
        if($vendor!='')
            $index['vendorName'] = Vendor::withTrashed()->where('id',$vendor)->first()->name;
        else $index['vendorName'] = null;
        $index['vendors'] = Vendor::withTrashed()->whereIn('type',['Fuel','fuel'])->pluck('name','id');
		$index['fuel_types'] = FuelType::pluck('fuel_name','id');
		$index['request'] = $request->all();
		$index['result'] = "";
        // dd($index);
        return view('vendors.report',$index);
    }

    public function report_print(Request $request){
        // dd($request->all());
        $vendor = $request->vendor;
        $fuel_type = $request->fuel_type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $from_date = empty($from_date) ? FuelModel::orderBy('date','ASC')->take(1)->first('date')->date : $from_date;
        $to_date = empty($to_date) ? FuelModel::orderBy('date','DESC')->take(1)->first('date')->date : $to_date;

        if(empty($vendor) && empty($fuel_type))
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date]);
        elseif(empty($vendor))
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date])->where('fuel_type',$fuel_type);
        elseif(empty($fuel_type))
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date])->where('vendor_name',$vendor);
        else
            $fuelModel = FuelModel::whereBetween('date',[$from_date,$to_date])->where('vendor_name',$vendor)->where('fuel_type',$fuel_type);

        $index['fuel'] = $fuelModel->orderBy('date','ASC')->get();
        // $index['fuelTotal'] = $fuelModel->select(DB::raw('SUM(qty) as qty'),DB::raw('AVG(cost_per_unit) as per_unit'),DB::raw('SUM(qty) * AVG(cost_per_unit) as amount'))->first();
        foreach($index['fuel'] as $sum){
            $summation[] = $sum->qty * $sum->cost_per_unit;
        }
        $index['fuelTotal'] = array_sum($summation);
        $index['date'] = collect(['from_date'=>$from_date,'to_date'=>$to_date]);
        $index['is_vendor'] = !empty($vendor) ? true : false;
        if($vendor!='')
            $index['vendorName'] = Vendor::withTrashed()->where('id',$vendor)->first()->name;
        else $index['vendorName'] = null;

        // $index['req'] = $request->all();
        // dd($index);
		return view('vendors.report-print',$index);
    }

}
