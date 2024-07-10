<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers as CustomerRequest;
use App\Http\Requests\ImportRequest;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Importer;
use Validator;

class CustomersController extends Controller
{
    public function importCutomers(ImportRequest $request)
    {

        $file = $request->excel;
        $destinationPath = './assets/samples/'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $file->move($destinationPath, $fileName);
        // dd($fileName);
        $excel = Importer::make('Excel');
        $excel->load('assets/samples/' . $fileName);
        $collection = $excel->getCollection()->toArray();
        array_shift($collection);
        // dd($collection);
        foreach ($collection as $customer) {
            if ($customer[3] != null) {
                $id = User::create([
                    "name" => $customer[0] . " " . $customer[1],
                    "email" => $customer[3],
                    "password" => bcrypt($customer[6]),
                    "user_type" => "C",
                    "api_token" => str_random(60),
                ])->id;
                $user = User::find($id);
                $user->first_name = $customer[0];
                $user->last_name = $customer[1];
                $user->address = $customer[5];
                $user->mobno = $customer[2];
                if ($customer[4] == "female") {
                    $user->gender = 0;
                } else {
                    $user->gender = 1;
                }
                $user->save();
            }
        }
        return back();
    }

    public function index()
    {
        $data['data'] = User::whereUser_type("C")->orderBy('id', 'desc')->get();
        return view("customers.index", $data);
    }
    public function create()
    {
        return view("customers.create");
    }
    public function store(CustomerRequest $request)
    {
        // dd($request->all());
        $id = User::create([
            "name" => $request->get("company_name"),
            "gstin" => $request->get("company_gst"),
            "email" => $request->get("email"),
            "user_type" => "C",
            "api_token" => str_random(60),
        ])->id;

        // dd($id);
        $user = User::find($id);
        // dd($user);
        $user->address = $request->get("address");
        $user->mobno = $request->get("phone");
        $user->opening_balance = $request->get("opening_balance");
        $user->opening_remarks = $request->get("opening_remarks");
        $user->save();

        return redirect()->route("customers.index");
    }
    public function ajax_store(Request $request)
    {
        // return response()->json($request);
        $v = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_gst' => 'required',
            'email' => 'unique:users,email',
            'phone' => 'numeric',
        ]);

        if ($v->fails()) {
            $d = 0;

        } else {
            $id = User::create([
                "name"=>$request->get("company_name"),
                "gstin"=>$request->get("company_gst"),
                "email" => $request->get("email"),
                "password" => bcrypt("password"),
                "user_type" => "C",
                "api_token" => str_random(60),
            ])->id;
            $user = User::find($id);
            $user->address = $request->get("address");
            $user->mobno = $request->get("phone");
            $user->save();
            $d = User::whereUser_type("C")->get(["id", "name as text"]);

        }

        return $d;

    }
    public function destroy(Request $request)
    {
        // User::find($request->get('id'))->get_detail()->delete();
        User::find($request->get('id'))->user_data()->delete();
        User::find($request->get('id'))->delete();

        return redirect()->route('customers.index');
    }

    public function edit($id)
    {
        $index['data'] = User::whereId($id)->first();
        return view("customers.edit", $index);
    }
    public function update(CustomerRequest $request)
    {

        $user = User::find($request->id);
        $user->name = $request->get("company_name");
        $user->gstin = $request->get("company_gst");
        $user->email = $request->get('email');
        // $user->password = bcrypt($request->get("password"));
        // $user->save();
        $user->address = $request->get("address");
        $user->mobno = $request->get("phone");
        $user->opening_balance = $request->get("opening_balance");
        $user->opening_remarks = $request->get("opening_remarks");
        $user->save();

        return redirect()->route("customers.index");
    }

    public function bulk_delete(Request $request)
    {
        // dd($request->all());
        User::whereIn('id', $request->ids)->delete();
        // return redirect('admin/customers');
        return back();
    }

    public function view_event(Request $request){
        // dd($request->id);
        $data['customer'] = User::where('id',$request->id)->first();
        // dd($data);
        return view('customers.view_event',$data);
    }
}
