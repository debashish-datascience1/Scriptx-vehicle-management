<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartsRequest;
use App\Model\PartsCategoryModel;
use App\Model\PartsModel;
use App\Model\PartsDetails;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\Manufacturer;
use App\Model\PartsInvoice;
use App\Model\PartStock;
use App\Model\UnitModel;
use App\Model\Vendor;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PartsController extends Controller
{

    public function index()
    {
        
        $index['data'] = PartsModel::orderBy('id', 'desc')->get();
        
        // dd($index);
        return view("parts.index", $index);
    }


    public function create()
    {
        $categories = PartsCategoryModel::pluck('name','id')->toArray();
        $manufacturers = Manufacturer::pluck('name','id')->toArray();
        $units = UnitModel::pluck('name','id')->toArray();
        // array_push($categories,"Add New");
        // array_push($manufacturers,"Add New");
        // array_push($units,"Add New");
        $data['categories'] = $categories;
        $data['manufacturers'] = $manufacturers;
        $data['units'] = $units;
        
        // $data['categories'] = ::pluck('name','id');
        //  dd($data);
        return view("parts.create", $data);
    }

    public function destroy(Request $request)
    {
       // dd('delete');
    //    dd($request->all());
        PartsModel::find($request->id)->delete();
        return redirect()->route('parts.index');
    }

    public function get_parts_form(Request $request)
    {  
        // dd($request->all());
        $vendors = Vendor::pluck('name','id');
        $categories = PartsCategoryModel::pluck('name','id');   
        return view('parts.parts_form',compact('vendors', 'categories'));      
    }


    private function upload_file($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = uniqid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $x = PartsModel::find($id)->update([$field => $fileName1]);

    }

    public function edit($id)
    {
        // dd($id);
        $index['data'] = PartsModel::find($id);
        $index['categories'] = PartsCategoryModel::pluck('name','id')->toArray();
        $index['manufacturers'] = Manufacturer::pluck('name','id')->toArray();
        $index['units'] = UnitModel::pluck('name','id')->toArray();
        
        return view("parts.edit", $index);
    }

    public function stock($id)
    {
        $data['data'] = PartStock::wherePart_id($id)->get();
        return view("parts.stocks", $data);
    }

    public function update(PartsRequest $request,PartsModel $part)
    {
        // dd($part);
        // dd($request->all());
        $item = $request->item;
        $category_id = $request->category_id;
        $patCat = PartsCategoryModel::find($category_id);
        $manufacturer = $request->manufacturer;
        $manufac = Manufacturer::find($manufacturer);
        $existsCheck = PartsModel::where(['item'=>$item,'category_id'=>$category_id,'manufacturer'=>$manufacturer,'id'=>!$part->id]);
        if(!$existsCheck->exists()){
            $formData = $request->all();
            unset($formData['_token']);
            unset($formData['_method']);
            PartsModel::where('id',$part->id)->update($formData);
        }else{
            return back()->withErrors("$item $patCat->name ($manufac->name) already exists");
        }

        return redirect()->back();
    } 
    public function store(Request $request)
    {
        // dd($request->all());
        $item = $request->item;
        $category_id = $request->category_id;
        $patCat = PartsCategoryModel::find($category_id);
        $manufacturer = $request->manufacturer;
        $manufac = Manufacturer::find($manufacturer);
        $formData = $request->all();
        unset($formData['_token']);
        // dd($formData);
        $existsCheck = PartsModel::where(['item'=>$item,'category_id'=>$category_id,'manufacturer'=>$manufacturer]);
        if(!$existsCheck->exists()){
            PartsModel::create($formData);
            return redirect()->route("parts.index");
        }
        else
            return back()->withErrors("$item $patCat->name ($manufac->name) already exists");
    }

    public function bulk_delete(Request $request)
    {
        
        $from_id=Transaction::select('id')->where('param_id',26)->where('type',24)->whereIn('from_id', $request->get('ids'))->get();

        IncomeExpense::whereIn('transaction_id',$from_id)->delete();
        Transaction::where('param_id',26)->where('type',24)->whereIn('from_id',$request->get('ids'))->delete();
        PartsDetails::whereIn('parts_id',$request->get('ids'))->delete();
        PartsModel::whereIn('id',$request->get('ids'))->delete();
        return back();
        
    }

    public function add_stock(Request $request)
    {
        $part = PartsModel::find($request->part_id);
        $part->stock = $part->stock + $request->stock;
        $part->save();
        return back();
    }
    

    public function view_event($id){
        $index['row']=PartsModel::find($id);
        return view('parts.view_event',$index);
    }
}
