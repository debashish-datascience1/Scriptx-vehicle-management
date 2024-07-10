<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UnitModel;
use Auth;

class UnitController extends Controller {

    public function index()
    {
        $index['data'] = UnitModel::orderBy('id','DESC')->get();
        // dd(Manufacturer::get());
        return view('unit.index',$index);
    }

    public function create(){
        return view('unit.create');
    }

    public function store(Request $request){
        // dd($request->toArray());
        $form_data = $request->all();
        unset($form_data['_token']);
        UnitModel::create($form_data);
        return redirect()->route('unit.index');
    }

    public function edit(UnitModel $unit){
        // dd($leave);
        $index['unit'] = $unit;
        // dd($unit);
        return view('unit.edit',$index);
    }

    public function update(UnitModel $unit,Request $request){
        //Code
        // dd($request->all());
        
        $unit->name = $request->name;
        $unit->short_name = $request->short_name;
        $unit->save();
        return redirect()->back();
    }

    public function destroy(UnitModel $unit){
        //Code
       $unit->delete();
       return redirect()->back();
    }

}