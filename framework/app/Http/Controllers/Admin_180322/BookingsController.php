<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Helpers\Helper;
use App\Mail\DriverBooked;
use App\Mail\VehicleBooked;
use App\Model\Address;
use App\Model\AdvanceDriver;
use App\Model\BookingIncome;
use App\Model\BookingPaymentsModel;
use App\Model\Bookings;
use App\Model\Hyvikk;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\ServiceReminderModel;
use App\Model\User;
use App\Model\VehicleModel;
use App\Model\Params;
use App\Model\Transaction;
use App\Model\IncomeExpense;
use App\Model\BankAccount;
use App\Model\BankTransaction;
use App\Model\DailyAdvance;
use Auth;
use Hash;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use PushNotification;
use Illuminate\Support\Facades\Storage;

class BookingsController extends Controller
{

    public function index()
    {
        //for json load
        // Helper::toJSON(['param_id'=>'transaction']);
        // $param_id = 18;
        // Helper::housekeeping($param_id);
        // $put = Storage::disk('public')->get(Helper::fileFromParam($param_id));
        // $object = (array)json_decode($put);
        // $collection = Bookings::hydrate($object);
        // // dd($collection);
        // $data['data'] = $collection->flatten();   // get rid of unique_id_XXX

        //for db load
        $data['data'] = Bookings::orderBy('pickup', 'DESC')->paginate(25);

        foreach ($data['data'] as $b) {
            // $transData = Transaction::where(['from_id'=>$b->id,'param_id'=>18]);
            // $transa = $transData->exists() ? $transData->first() : null;
            // $b->transid = !empty($transa->id) ? $transa->id : null;
            // $b->invoice_id = $transData->where('advance_for',null)->exists() ? $transData->where('advance_for',null)->first()->transaction_id : null;
            $transData = Transaction::where(['from_id' => $b->id, 'param_id' => 18])->where(function ($query) {
                return $query->where('advance_for', null)
                    ->orWhere('advance_for', '!=', 21);
            });
            $transa = $transData->exists() ? $transData->first() : null;
            $b->transid = !empty($transa->id) ? $transa->id : null;
            $b->invoice_id = !empty($transa->transaction_id) ? $transa->transaction_id : null;
            $b->inc_rows = !empty($transa->id) ? IncomeExpense::where('transaction_id', $transa->id)->count() : 0;
            // dd($b);
        }
        $data['types'] = IncCats::get();
        // dd($data);
        return view("bookings.index", $data);
    }

    // public function index()
    // {

    //     // dd(Bookings::get('journey*'));
    //     if (Auth::user()->user_type == "C") {
    //         $data['data'] = Bookings::where('customer_id', Auth::user()->id)->orderBy('id', 'desc')->get();
    //     } elseif (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
    //         $data['data'] = Bookings::orderBy('id', 'desc')->get();
    //     } else {
    //         $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
    //         $data['data'] = Bookings::whereIn('vehicle_id', $vehicle_ids)->orderBy('id', 'desc')->get();
    //     }

    //     foreach($data['data'] as $b){
    //         $transData = Transaction::where(['from_id'=>$b->id,'param_id'=>18]);
    //         $transid = $transData->exists() ? $transData->first()->id : null;
    //         $b->transid = $transid;
    //         $b->inc_rows = !empty($transid) ? IncomeExpense::where('transaction_id',$transid)->count() : 0;
    //     }

    //     $data['types'] = IncCats::get();
    //     // dd($data);
    //     return view("bookings.index", $data);
    // }

    //new added forbooking print
    public function print_booking_new($id)
    {

        $data['data'] = Bookings::find($id);
        $data['params'] = DB::table('params')->where('id', Bookings::find($id)->getMeta('loadtype'))->first('label');

        if ($data['params']->label == "")
            $data['params']->label = "N/A";
        return view("bookings.print_bookings_vechile", $data);
    }
    //end

    public function receipt($id)
    {
        $data['id'] = $id;
        $data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
        // $data['info'] = IncomeModel::whereId($book['income_id'])->first();
        $data['booking'] = Bookings::find($id);
        return view("bookings.receipt", $data);
    }

    function print($id)
    {
        $data['i'] = $book = BookingIncome::whereBooking_id($id)->first();
        // $data['info'] = IncomeModel::whereId($book['income_id'])->first();
        $data['booking'] = Bookings::whereId($id)->get()->first();
        return view("bookings.print", $data);
    }

    public function payment($id)
    {
        $booking = Bookings::find($id);
        $booking->payment = 1;
        $booking->payment_method = "cash";
        $booking->save();
        BookingPaymentsModel::create(['method' => 'cash', 'booking_id' => $id, 'amount' => $booking->tax_total, 'payment_details' => null, 'transaction_id' => null, 'payment_status' => "succeeded"]);
        return redirect()->route('bookings.index');
    }

    public function complete_post(Request $request)
    {
        dd($request->all());
        $booking = Bookings::find($request->get("booking_id"));

        $booking->setMeta([
            'customerId' => $request->get('customerId'),
            'vehicleId' => $request->get('vehicleId'),
            'day' => $request->get('day'),
            'mileage' => $request->get('mileage'),
            'waiting_time' => $request->get('waiting_time'),
            'date' => $request->get('date'),
            'total' => $request->get('total'),
            'total_kms' => $request->get('mileage'),
            // 'ride_status' => 'Completed',edit
            'tax_total' => $request->get('tax_total'),
            'total_tax_percent' => $request->get('total_tax_charge'),
            'total_tax_charge_rs' => $request->total_tax_charge_rs,
        ]);
        $booking->save();

        $id = IncomeModel::create([
            "vehicle_id" => $request->get("vehicleId"),
            // "amount" => $request->get('total'),
            "amount" => $request->get('tax_total'),
            "user_id" => $request->get("customerId"),
            "date" => $request->get('date'),
            "mileage" => $request->get("mileage"),
            "income_cat" => $request->get("income_type"),
            "income_id" => $booking->id,
            "tax_percent" => $request->get('total_tax_charge'),
            "tax_charge_rs" => $request->total_tax_charge_rs,
        ])->id;

        BookingIncome::create(['booking_id' => $request->get("booking_id"), "income_id" => $id]);
        $xx = Bookings::whereId($request->get("booking_id"))->first();
        // $xx->status = 1;
        $xx->receipt = 1;
        $xx->save();

        return redirect()->route("bookings.index");
    }

    public function complete($id)
    {

        $xx = Bookings::find($id);
        $xx->status = 1;
        $xx->ride_status = "Completed";
        $xx->save();
        return redirect()->route("bookings.index");
    }

    public function get_driver(Request $request)
    {

        $from_date = $request->get("from_date");
        $to_date = $request->get("to_date");
        $req_type = $request->get("req");
        if ($req_type == "new") {
            $q = "select id,name as text from users where user_type='D' and deleted_at is null and id not in (select driver_id from bookings where  deleted_at is null   and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";

            $d = collect(DB::select(DB::raw($q)));

            $r['data'] = $d;
        } else {
            $id = $request->get("id");
            $current = Bookings::find($id);
            $q = "select id,name as text from users where user_type='D' and id not in (select driver_id from bookings where  id!=" . $id . " and deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
            $d = collect(DB::select(DB::raw($q)));

            $chk = $d->where('id', $current->driver_id);
            $r['show_error'] = "yes";
            if (count($chk) > 0) {
                $r['show_error'] = "no";
            }
            $new = array();

            foreach ($d as $ro) {
                if ($ro->id === $current->driver_id) {
                    array_push($new, array("id" => $ro->id, "text" => $ro->text, 'selected' => true));
                } else {
                    array_push($new, array("id" => $ro->id, "text" => $ro->text));
                }
            }

            $r['data'] = $new;
        }

        return $r;
    }

    public function showAllVehicle(Request $request)
    {
        if (Hash::check($request->hash, Auth::user()->password)) {
            // Getting Vehicles
            $q = "select id,concat(make,' - ',model,' - ',license_plate) as text from vehicles where in_service=1 and deleted_at is null";
            $d = collect(DB::select(DB::raw($q)));
            // return $d;
            $new = array();
            // array_push($new, array("id" => "", "text" => ""));
            foreach ($d as $k => $ro) {
                if ($k == 0)
                    array_push($new, array("id" => $ro->id, "text" => $ro->text, "selected" => true));
                else
                    array_push($new, array("id" => $ro->id, "text" => $ro->text));
            }

            $r['data'] = $new;
            // return $r;

            // Getting Driver
            $qq = "select id,name as text from users where user_type='D' and deleted_at is null";
            $xx = collect(DB::select(DB::raw($qq)));
            $newD = array();

            foreach ($xx as $ky => $row) {
                if ($ky == 0)
                    array_push($newD, array("id" => $row->id, "text" => $row->text, "selected" => true));
                else
                    array_push($newD, array("id" => $row->id, "text" => $row->text));
            }
            $r['data_driver'] = $newD;
            return ['resp' => $r, 'status' => true];
        } else {
            return ['resp' => null, 'status' => false];
        }
        return response()->json($request);
    }

    public function get_vehicle(Request $request)
    {

        $from_date = $request->get("from_date");
        $to_date = $request->get("to_date");
        $req_type = $request->get("req");

        if ($req_type == "new") {
            $xy = array();
            if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
                $q = "select id,concat(make,' - ',model,' - ',license_plate) as text from vehicles where in_service=1 and deleted_at is null  and  id not in(select vehicle_id from bookings where  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
            } else {
                $q = "select id,concat(make,' - ',model,' - ',license_plate) as text from vehicles where in_service=1 and deleted_at is null and group_id=" . Auth::user()->group_id . " and  id not in(select vehicle_id from bookings where  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
            }

            $d = collect(DB::select(DB::raw($q)));

            $new = array();
            foreach ($d as $ro) {

                array_push($new, array("id" => $ro->id, "text" => $ro->text));
            }

            $r['data'] = $new;
            return $r;
        } else {
            $id = $request->get("id");
            $current = Bookings::find($id);
            if ($current->vehicle_typeid != null) {
                $condition = " and type_id = '" . $current->vehicle_typeid . "'";
            } else {
                $condition = "";
            }

            if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
                $q = "select id,concat(make,' - ',model,' - ',license_plate) as text from vehicles where in_service=1 " . $condition . " and id not in (select vehicle_id from bookings where id!=$id and  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
            } else {
                $q = "select id,concat(make,' - ',model,' - ',license_plate) as text from vehicles where in_service=1 " . $condition . " and group_id=" . Auth::user()->group_id . " and id not in (select vehicle_id from bookings where id!=$id and  deleted_at is null  and ((dropoff between '" . $from_date . "' and '" . $to_date . "' or pickup between '" . $from_date . "' and '" . $to_date . "') or (DATE_ADD(dropoff, INTERVAL 10 MINUTE)>='" . $from_date . "' and DATE_SUB(pickup, INTERVAL 10 MINUTE)<='" . $to_date . "')))";
            }

            $d = collect(DB::select(DB::raw($q)));
            $chk = $d->where('id', $current->vehicle_id);
            $r['show_error'] = "yes";
            if (count($chk) > 0) {
                $r['show_error'] = "no";
            }

            $new = array();
            foreach ($d as $ro) {
                if ($ro->id === $current->vehicle_id) {
                    array_push($new, array("id" => $ro->id, "text" => $ro->text, "selected" => true));
                } else {
                    array_push($new, array("id" => $ro->id, "text" => $ro->text));
                }
            }
            $r['data'] = $new;
            return $r;
        }
    }

    public function calendar_event($id)
    {
        $data['booking'] = Bookings::find($id);
        return view("bookings.event", $data);
    }
    public function calendar_view()
    {
        $booking = Bookings::where('user_id', Auth::user()->id)->exists();
        return view("bookings.calendar", compact('booking'));
    }

    public function service_view($id)
    {
        $data['service'] = ServiceReminderModel::find($id);
        return view("bookings.service_event", $data);
    }

    public function calendar(Request $request)
    {
        $data = array();
        $start = $request->get("start");
        $end = $request->get("end");
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $b = Bookings::where("pickup", ">=", $start)->where("dropoff", "<=", $end)->get();
        } else {
            $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
            $b = Bookings::whereIn('vehicle_id', $vehicle_ids)->where("pickup", ">=", $start)->where("dropoff", "<=", $end)->get();
        }

        foreach ($b as $booking) {
            $x['start'] = $booking->pickup;
            $x['end'] = $booking->dropoff;
            if ($booking->status == 1) {
                $color = "grey";
            } else {
                $color = "red";
            }
            $x['backgroundColor'] = $color;
            $x['title'] = $booking->customer->name;
            $x['id'] = $booking->id;
            $x['type'] = 'calendar';

            array_push($data, $x);
        }

        $reminders = ServiceReminderModel::get();
        foreach ($reminders as $r) {
            $interval = substr($r->services->overdue_unit, 0, -3);
            $int = $r->services->overdue_time . $interval;
            $date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))));
            if ($r->last_date != 'N/D') {
                $date = date('Y-m-d', strtotime($int, strtotime($r->last_date)));
            }

            $x['start'] = $date;
            $x['end'] = $date;

            $color = "green";

            $x['backgroundColor'] = $color;
            $x['title'] = $r->services->description;
            $x['id'] = $r->id;
            $x['type'] = 'service';
            array_push($data, $x);
        }
        return $data;
    }

    public function dough()
    {
        $startingAmount = BankAccount::find(1)->starting_amount;
        $deposits = $index['deposits'] = BankTransaction::where('bank_id', 1)->sum('amount');
        $total_dough = (float) $startingAmount +  (float) $deposits;
        $spent = $index['spent'] = Transaction::where(['bank_id' => 1, 'type' => 24])->sum('total');
        $rem = $total_dough - $spent;
        return $rem;
    }

    public function create()
    {
        $user = Auth::user()->group_id;
        $data['customers'] = User::where('user_type', 'C')->get();
        $data['payment_type'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
        $data['drivers'] = User::whereUser_type("D")->get();
        $data['addresses'] = Address::where('customer_id', Auth::user()->id)->get();
        $data['purse'] = $this->dough();
        $data['loadsetting'] = DB::table('params')->where('code', 'LoadSetting')->pluck('label', 'id');
        $data['mileagetypes'] = DB::table('params')->where('code', 'MileageType')->pluck('label', 'id');
        if ($user == null) {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where([['group_id', $user], ['in_service', '1']])->get();
        }
        // dd($data['vehicles']->first()->getMeta());
        return view("bookings.create", $data);
    }

    public function edit($id)
    {
        $booking = Bookings::whereId($id)->get()->first();
        // dd($booking);
        // dd($booking->getMeta());
        // dd($booking->vehicle_typeid);
        if ($booking->vehicle_typeid != null) {
            $condition = " and type_id = '" . $booking->vehicle_typeid . "'";
        } else {
            $condition = "";
        }
        $q = "select id,name,deleted_at from users where user_type='D' and deleted_at is null and id not in (select user_id from bookings where status=0 and  id!=" . $id . " and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
        // $drivers = collect(DB::select(DB::raw($q)));
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $q1 = "select * from vehicles where in_service=1" . $condition . " and deleted_at is null and id not in (select vehicle_id from bookings where status=0 and  id!=" . $id . " and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "'  or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
        } else {
            $q1 = "select * from vehicles where in_service=1" . $condition . " and deleted_at is null and group_id=" . Auth::user()->group_id . " and id not in (select vehicle_id from bookings where status=0 and  id!=" . $id . " and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $booking->pickup . "' and '" . $booking->dropoff . "'  or dropoff between '" . $booking->pickup . "' and '" . $booking->dropoff . "'))";
        }
        $vehi = collect(DB::select(DB::raw($q1)))->toArray();
        $vehicles = VehicleModel::hydrate($vehi);
        // $tt = Transaction::where(['from_id'=>$booking->id,'param_id'=>'22'])->orderBy('id','ASC')->first();
        $advDetails = $this->getAmount($booking->id);
        // foreach($vehicles as $v){
        //     // dd($v->id);
        //     dd(Bookings::where('id',$v->id)->get());
        // }
        // dd($advDetails->income_expense->amount);
        if (!empty($advDetails)) {
            $index['payment_method'] = $advDetails->income_expense->payment_method;
            $index['remarks'] = $advDetails->income_expense->remarks;
            $index['payment_amount'] = $advDetails->income_expense->amount;
        } else {
            $index['payment_method'] = null;
            $index['remarks'] = null;
            $index['payment_amount'] = null;
        }
        $index['drivers'] = User::whereUser_type("D")->get();
        $index['vehicles'] = $vehicles;
        $index['data'] = $booking;
        $index['purse'] = $this->dough();
        $index['loadsetting'] = DB::table('params')->where('code', 'LoadSetting')->pluck('label', 'id');
        $index['mileagetypes'] = DB::table('params')->where('code', 'MileageType')->pluck('label', 'id');
        $index['udfs'] = unserialize($booking->getMeta('udf'));
        $index['payment_type'] = Params::where('code', 'PaymentMethod')->pluck('label', 'id');
        // dd($index);
        return view("bookings.edit", $index);
    }

    public function destroy(Request $request)
    {
        // dd($request->get('id'));
        $id = $request->id;
        $trns = Transaction::where(['from_id' => $id, 'param_id' => 18]);
        $trns_id = $trns->first()->id;
        $trns->delete();
        // dd($trns_id);
        IncomeExpense::where('transaction_id', $trns_id)->delete();
        Bookings::find($id)->delete();
        //Advance Customer Ends
        Helper::toJSON(['param_id' => 18]);
        return redirect()->route('bookings.index');
    }

    protected function check_booking($pickup, $dropoff, $vehicle)
    {

        $chk = DB::table("bookings")
            ->where("status", 0)
            ->where("vehicle_id", $vehicle)
            ->whereNull("deleted_at")
            ->where("pickup", ">=", $pickup)
            ->where("dropoff", "<=", $dropoff)
            ->get();

        if (count($chk) > 0) {
            return false;
        } else {
            return true;
        }
    }
    private function upload_doc($file, $field, $id)
    {
        $destinationPath = './uploads'; // upload path
        $extension = $file->getClientOriginalExtension();
        $fileName1 = Str::uuid() . '.' . $extension;

        $file->move($destinationPath, $fileName1);
        $book = Bookings::find($id);
        $book->setMeta([$field => $fileName1]);
        $book->save();
    }
    public function getGrandTotal()
    {
        $credit = Transaction::where('type', 23)->sum('total');
        $debit = Transaction::where('type', 24)->sum('total');
        return (float) $credit - (float) $debit;
    }
    public function store(BookingRequest $request)
    {
        $blob = collect(Helper::decode($request->_blob));
        // dd($blob['duration']);
        // dd($blob);
        // dd($blob->loadprice);
        // dd($request->all());
        $datepickup = date("Y-m-d H:i:s", strtotime($request->get("pickup")));
        $datedropoff = date("Y-m-d H:i:s", strtotime($request->get("dropoff")));
        // dd($datepickup.'--'.$datedropoff);
        $xx = $this->check_booking($datepickup, $datedropoff, $request->get("vehicle_id"));
        // dd($xx);
        if ($xx) {
            unset($request->picklat);
            unset($request->_blob);
            unset($request->ddriver);
            unset($request->dmileage);

            $request->merge(['pickup' => $datepickup, 'dropoff' => $datedropoff]);
            // dd($request->all());
            $id = Bookings::create($request->all())->id;

            Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('pickup_addr')]);

            Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('dest_addr')]);

            $booking = Bookings::find($id);

            if ($request->file('challan') && $request->file('challan')->isValid()) {
                $this->upload_doc($request->file('challan'), 'challan', $booking->id);
            }

            $booking->user_id = $request->get("user_id");
            $booking->driver_id = $request->get('driver_id');
            $booking->mileage_type = $request->get('mileage_type');
            $booking->tmileage = $request->get('tmileage');
            // $dropoff = Carbon::parse($booking->dropoff);
            // $pickup = Carbon::parse($booking->pickup);
            // $diff = $pickup->diffInMinutes($dropoff);
            // $booking->note = $request->get('note');
            // $booking->duration = $diff;
            // $booking->udf = serialize($request->get('udf'));
            $booking->loadprice = $blob['loadprice'];
            $booking->loadqty = $blob['loadqty'];
            $booking->perltr = $blob['perltr']; //per litre price
            $booking->loadtype = $blob['loadtype']; //1->Trips,2->bags,3->quantity
            $booking->material = $request->material;
            $booking->initial_km = $request->has('initial_km') ? $request->initial_km : null;
            $booking->distance = $blob['distance'];
            $booking->duration_map = $blob['duration'];
            $booking->mileage = $blob['mileage'];
            $booking->pet_required = $blob['pet_required']; //in ltr
            $booking->petrol_price = $blob['petrol_price']; // total pet price
            $booking->total_price = $blob['total_price'];
            $booking->advance_pay = $blob['advance_pay'];
            $booking->payment_amount = $request->payment_amount;
            $booking->accept_status = 1; //0=yet to accept, 1= accept
            $booking->ride_status = "Upcoming";
            $booking->booking_type = 1;
            $booking->party_name = $request->party_name;
            $booking->narration = $request->narration;
            $booking->journey_date = date('d-m-Y', strtotime($datepickup));
            $booking->journey_time = date('H:i:s', strtotime($datepickup));
            // dd($booking);
            $is_saved = $booking->save();
            if ($is_saved) {
                //Advance to Driver Accounting Calculation starts
                if (!empty($blob['advance_pay'])) {
                    // $prevGrandTotal = $this->getGrandTotal();
                    $accountAdvance['from_id'] = $id; //Booking ID
                    $accountAdvance['type'] = 24; // Debit 
                    $accountAdvance['bank_id'] = 1; // Bank ID SELF CASH 
                    $accountAdvance['param_id'] = 18; //From Booking
                    $accountAdvance['advance_for'] = 21; //Driver Advance in Booking
                    $accountAdvance['total'] = $blob['advance_pay'];
                    // $accountAdvance['grandtotal'] = $prevGrandTotal-$blob['advance_pay'];

                    $transid = Transaction::create($accountAdvance)->id;
                    $trash = ['type' => 24, 'from' => 18, 'id' => $transid];
                    $transaction_id = Helper::transaction_id($trash);
                    Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                    $expense['transaction_id'] = $transid;
                    $expense['payment_method'] = 16; //Cash
                    $expense['date'] = date("Y-m-d H:i:s");
                    $expense['amount'] = $blob['advance_pay'];
                    $expense['remaining'] = 0;
                    $expense['remarks'] = null;

                    IncomeExpense::create($expense);
                }
                //Advance to Driver Accounting Calculation ends


                // Paid From Customer Accounting Calculation;
                if (!empty($request->total_pay)) {
                    // $prevGrandTotal = $this->getGrandTotal();
                    $account['from_id'] = $id; //Booking Id
                    $account['type'] = 23; //Credit 
                    $account['param_id'] = 18; //From Booking
                    $account['advance_for'] = !empty($request->payment_amount) ? 22 : null; //Customer Advance in Booking
                    $account['total'] = $request->total_pay;
                    // $account['grandtotal'] = $prevGrandTotal+$request->total_pay;

                    $transid = Transaction::create($account)->id;
                    $trash = ['type' => 23, 'from' => 18, 'id' => $transid];
                    $transaction_id = Helper::transaction_id($trash);
                    Transaction::find($transid)->update(['transaction_id' => $transaction_id]);


                    if (!empty($request->payment_amount)) {
                        $payamount = $request->payment_amount;
                        $remain = $request->total_pay - $request->payment_amount;
                    } else {
                        $payamount = 0;
                        $remain = $request->total_pay;
                    }

                    $income['transaction_id'] = $transid;
                    $income['payment_method'] = empty($request->payment_type) ? 16 : $request->payment_type;
                    $income['date'] = date("Y-m-d H:i:s");
                    $income['amount'] = $payamount;
                    $income['remaining'] = $remain;
                    $income['remarks'] = empty($request->remarks) ? null : $request->remarks;

                    IncomeExpense::create($income);
                }
            }

            // $mail = Bookings::find($id);
            // $this->booking_notification($booking->id);
            // // browser notification
            // $this->push_notification($booking->id);
            // if (Hyvikk::email_msg('email') == 1) {
            //     Mail::to($mail->customer->email)->send(new VehicleBooked($booking));
            //     // Mail::to($mail->driver->email)->send(new DriverBooked($booking));

            // }
            // Helper::toJSON(['param_id'=>18]);
            return redirect()->route("bookings.index");
        } else {
            return redirect()->route("bookings.create")->withErrors(["error" => "Selected Vehicle is not Available in Given Timeframe"])->withInput();
        }
    }

    public function push_notification($id)
    {
        $booking = Bookings::find($id);
        $auth = array(
            'VAPID' => array(
                'subject' => 'Alert about new post',
                'publicKey' => 'BKt+swntut+5W32Psaggm4PVQanqOxsD5PRRt93p+/0c+7AzbWl87hFF184AXo/KlZMazD5eNb1oQVNbK1ti46Y=',
                'privateKey' => 'NaMmQJIvddPfwT1rkIMTlgydF+smNzNXIouzRMzc29c=', // in the real world, this would be in a secret file
            ),
        );

        $select1 = DB::table('push_notification')->select('*')->whereIn('user_id', [$booking->user_id])->get()->toArray();

        $webPush = new WebPush($auth);

        foreach ($select1 as $fetch) {
            $sub = Subscription::create([
                'endpoint' => $fetch->endpoint, // Firefox 43+,
                'publicKey' => $fetch->publickey, // base 64 encoded, should be 88 chars
                'authToken' => $fetch->authtoken, // base 64 encoded, should be 24 chars
                'contentEncoding' => $fetch->contentencoding,
            ]);
            $user = User::find($fetch->user_id);

            $title = __('fleet.new_booking');
            $body = __('fleet.customer') . ": " . $booking->customer->name . ", " . __('fleet.pickup') . ": " . date(Hyvikk::get('date_format') . ' g:i A', strtotime($booking->pickup)) . ", " . __('fleet.pickup_addr') . ": " . $booking->pickup_addr . ", " . __('fleet.dropoff_addr') . ": " . $booking->dest_addr;
            $url = url('admin/bookings');

            $array = array(
                'title' => $title ?? "",
                'body' => $body ?? "",
                'img' => url('assets/images/' . Hyvikk::get('icon_img')),
                'url' => $url ?? url('admin/'),
            );
            $object = json_encode($array);

            if ($fetch->user_id == $user->id) {
                $test = $webPush->sendNotification($sub, $object);
            }
            foreach ($webPush->flush() as $report) {

                $endpoint = $report->getRequest()->getUri()->__toString();
            }
        }
    }
    public function getAmount($id)
    {
        if (Transaction::where(['from_id' => $id, 'param_id' => 18, 'advance_for' => 22])->exists()) {
            $tt = Transaction::where(['from_id' => $id, 'param_id' => 18, 'advance_for' => 22])->first();
        } else {
            $tt = null;
        }
        return $tt;
    }
    public function update(Request $request)
    {
        // dd($request->toArray());
        $booking = Bookings::whereId($request->get("id"))->first();
        $blob = collect(Helper::decode($request->_blob));
        // dd($blob);
        // dd($blob->loadprice);
        // dd($request->toArray());
        $datepickup = date("Y-m-d H:i:s", strtotime($request->get("pickup")));
        $datedropoff = date("Y-m-d H:i:s", strtotime($request->get("dropoff")));
        // dd($datepickup.'--'.$datedropoff);
        // $xx = $this->check_booking($datepickup, $datedropoff, $request->get("vehicle_id"));
        // dd($xx);
        // dd(12);
        $bookid = $booking->id;

        unset($request->picklat);
        unset($request->_blob);
        unset($request->ddriver);
        unset($request->dvehicle);
        unset($request->dmileage);

        $request->merge(['pickup' => $datepickup, 'dropoff' => $datedropoff]);
        if ($request->file('challan') && $request->file('challan')->isValid()) {
            $this->upload_doc($request->file('challan'), 'challan', $booking->id);
        }
        $booking->user_id = $request->get("user_id");
        $booking->vehicle_id = $request->get("vehicle_id");
        $booking->driver_id = $request->get('driver_id');
        $booking->loadprice = $blob['loadprice'];
        $booking->loadqty = $blob['loadqty'];
        $booking->perltr = $blob['perltr']; //per litre price
        $booking->loadtype = $blob['loadtype']; //1->Trips,2->bags,3->quantity
        $booking->material = $request->material;
        $booking->mileage_type = $request->has('mileage_type'); //mileage type
        $booking->tmileage = $blob['tmileage']; //mileage value
        $booking->initial_km = $request->has('initial_km') ? $request->initial_km : null;
        $booking->distance = $blob['distance'];
        $booking->duration_map = $blob['duration'];
        $booking->mileage = $blob['mileage'];
        $booking->pet_required = $blob['pet_required']; //in ltr
        $booking->petrol_price = $blob['petrol_price']; // total pet price
        $booking->total_price = $blob['total_price'];
        $booking->advance_pay = $blob['advance_pay'];
        $booking->accept_status = 1; //0=yet to accept, 1= accept
        if ($booking->ride_status == null) {
            $booking->ride_status = "Upcoming";
        }
        $booking->booking_type = 1;
        $booking->journey_date = date('d-m-Y', strtotime($datepickup));
        $booking->journey_time = date('H:i:s', strtotime($datepickup));

        $booking->pickup = $request->get("pickup");
        $booking->dropoff = $request->get("dropoff");
        $booking->pickup_addr = $request->get("pickup_addr");
        $booking->dest_addr = $request->get("dest_addr");
        // dd($booking);
        $booking->save();
        // $booking->id
        //Account Updating
        $trns = Transaction::where(['from_id' => $booking->id, 'param_id' => 18]);
        $total_p = (float) $blob['total_price'];
        $advance_p = empty($blob['advance_pay']) ? 0 : (float) $blob['advance_pay'];
        $trns->update(['total' => $total_p]);
        $trns_id = $trns->first()->id;
        // dd($trns_id);

        $rem = $total_p - $advance_p;
        IncomeExpense::where('transaction_id', $trns_id)->update(['amount' => $advance_p, 'remaining' => $rem]);
        // Helper::toJSON(['param_id' => 18]);
        return redirect()->route('bookings.index');
    }

    public function prev_address(Request $request)
    {
        $booking = Bookings::where('customer_id', $request->get('id'))->orderBy('id', 'desc')->first();
        if ($booking != null) {
            $r = array('pickup_addr' => $booking->pickup_addr, 'dest_addr' => $booking->dest_addr);
        } else {
            $r = array('pickup_addr' => "", 'dest_addr' => "");
        }

        return $r;
    }

    public function print_bookings()
    {
        if (Auth::user()->user_type == "C") {
            $data['data'] = Bookings::where('customer_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        } else {
            $data['data'] = Bookings::orderBy('id', 'desc')->get();
        }

        return view('bookings.print_bookings', $data);
    }

    public function booking_notification($id)
    {

        $booking = Bookings::find($id);
        $data['success'] = 1;
        $data['key'] = "upcoming_ride_notification";
        $data['message'] = 'New Ride has been Assigned to you.';
        $data['title'] = "New Upcoming Ride for you !";
        $data['description'] = $booking->pickup_addr . " - " . $booking->dest_addr . " on " . date('d-m-Y', strtotime($booking->pickup));
        $data['timestamp'] = date('Y-m-d H:i:s');
        $data['data'] = array(
            'rideinfo' => array(

                'booking_id' => $booking->id,
                'source_address' => $booking->pickup_addr,
                'dest_address' => $booking->dest_addr,
                'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
                'ridestart_timestamp' => null,
                'journey_date' => date('d-m-Y', strtotime($booking->pickup)),
                'journey_time' => date('H:i:s', strtotime($booking->pickup)),
                'ride_status' => "Upcoming"
            ),
            'user_details' => array('user_id' => $booking->customer_id, 'user_name' => $booking->customer->name, 'mobno' => $booking->customer->getMeta('mobno'), 'profile_pic' => $booking->customer->getMeta('profile_pic')),
        );
        // dd($data);
        $driver = User::find($booking->driver_id);

        if ($driver->getMeta('fcm_id') != null && $driver->getMeta('is_available') == 1) {
            PushNotification::app('appNameAndroid')
                ->to($driver->getMeta('fcm_id'))
                ->send($data);
        }
    }

    public function bulk_delete(Request $request)
    {
        Bookings::whereIn('id', $request->ids)->delete();
        IncomeModel::whereIn('income_id', $request->ids)->where('income_cat', 1)->delete();
        return back();
    }

    public function cancel_booking(Request $request)
    {
        $booking = Bookings::find($request->cancel_id);
        $booking->ride_status = "Cancelled";
        $booking->reason = $request->reason;
        $booking->save();
        // if booking->status != 1 then delete income record
        IncomeModel::where('income_id', $request->cancel_id)->where('income_cat', 1)->delete();
        return back()->with(['msg' => 'Booking cancelled successfully!']);
    }

    public function getblob(Request $request)
    {
        // dd($request->toArray());
        return Helper::encode($request->toArray());
        // return Helper::decode($bc);
        // dd(Helper::encode($request->toArray()));
    }

    public function dropofftime(Request $request)
    {

        $exploded = explode(" ", $request->duration);
        //Getting values
        if (count($exploded) == 4) {
            $exploded[3] = 'minutes';
            $hours = $exploded[0] . ' ' . $exploded[1];
            $minutes = $exploded[2] . ' ' . $exploded[3];
            $newDuration = date('Y-m-d H:i:s', strtotime("+$hours +$minutes", strtotime($request->currTime)));
        }
        if (count($exploded) == 2) {
            $exploded[1] = 'minutes';
            $minutes = $exploded[0] . ' ' . $exploded[1];
            $newDuration = date('Y-m-d H:i:s', strtotime("+$minutes", strtotime($request->currTime)));
        }
        // for Booking Summary
        $pick = date('d-m-Y g:i:s A', strtotime($request->currTime));
        $drop = date('d-m-Y g:i:s A', strtotime($newDuration));

        $return = ['start' => $request->currTime, 'end' => $newDuration, 'start_sum' => $pick, 'drop_sum' => $drop];
        return response()->json($return);
    }

    public function timeperltr(Request $request)
    {
        // return response()->json($request->all());
        $vmodel = VehicleModel::where('id', $request->vehicle)->first();
        $tavg = empty($vmodel->getMeta('time_average')) || $vmodel->getMeta('time_average') == ":" ? null : $vmodel->getMeta('time_average');

        if (empty($tavg)) {
            $inMinTotal = null;
            $petrolReq = null;
        } else {
            $timeAvgMins = Helper::toMinutes(Helper::digitsToTime($tavg));
            $inMinTotal = Helper::toMinutes($request->duration);
            $petrolReq = Helper::properDecimals($inMinTotal / $timeAvgMins);
        }
        return response()->json(['avg' => $petrolReq]);
    }

    public function view_event(Request $request)
    {
        $data['booking'] = Bookings::find($request->id);
        $data['params'] = DB::table('params')->where('id', Bookings::find($request->id)->getMeta('loadtype'))->first('label');
        // dd($data);
        if ($data['params']->label == "")
            $data['params']->label = "N/A";

        $data['advances'] = AdvanceDriver::where('booking_id', $request->id)->get();
        $data['advTotal'] = AdvanceDriver::where('booking_id', $request->id)->sum('value');
        // dd($data);
        return view('bookings.view_event', $data);
    }

    public function get_required(Request $request)
    {
        // return $request->id;
        $vehicle = VehicleModel::find($request->id);
        // dd($vehicle);
        return response()->json(['driver' => $vehicle->getMeta('driver_id'), 'mileage' => $vehicle->getMeta('average'), 'vehicle' => $vehicle->id]);
    }

    public function getDistanceFromAddress(Request $request)
    {
        // return $request;
        //Get from-to address from booking ids
        $from_address = Bookings::find($request->booking_id)->dest_addr;
        $to_address = Bookings::find($request->next_booking)->pickup_addr;
        // $to_address = "sadasdasd";
        // return [$from_address,$to_address];
        //setting up data for google request
        $key = Hyvikk::api('server_apikey');
        $from_address = urlencode($from_address);
        $to_address = urlencode($to_address);

        $from_url = "https://maps.googleapis.com/maps/api/geocode/json?address={$from_address}&key={$key}";
        $to_url = "https://maps.googleapis.com/maps/api/geocode/json?address={$to_address}&key={$key}";

        //Requesting google for latitude and longitude of an address
        $from_response =  json_decode(file_get_contents($from_url), true);
        $to_response =  json_decode(file_get_contents($to_url), true);



        // return $from_response['results'][0]['geometry'];
        // Get latitude and longitude from the geodata
        // return $from_latitude    = $from_response['results'][0]['geometry']['location']['lat'];
        $from_latitude    = $from_response['results'][0]['geometry']['location']['lat'];
        $from_longitude    = $from_response['results'][0]['geometry']['location']['lng'];
        $to_latitude        = $to_response['results'][0]['geometry']['location']['lat'];
        $to_longitude    = $to_response['results'][0]['geometry']['location']['lng'];


        //Requesting google to get the distance and time from lat,lng
        $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$from_latitude},{$from_longitude}&destinations={$to_latitude},{$to_longitude}&key={$key}&mode=driving&sensor=false";
        $json = file_get_contents($details);

        return json_decode($json, true);
    }

    public function get_lat(Request $request)
    {
        $address = urlencode($request->addr);
        $key = Hyvikk::api('server_apikey');
        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$key}";

        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        return $resp = json_decode($resp_json, true);
    }

    public function modalcomplete(Request $request)
    {
        $index['data'] = Bookings::find($request->id);
        return view('bookings.markcomplete', $index);
    }

    public function modalroute(Request $request)
    {
        // dd($request->id);
        // $index['abc']=$request->id;
        $index['bookings'] = Bookings::where('id', $request->id)->first();
        // $from_ids = Transaction::where('param_id',18)->pluck('transaction_id','from_id');
        $index['next_booking'] = Transaction::where('param_id', 18)->where(function ($query) {
            return $query->where('advance_for', '!=', 21)
                ->orWhere('advance_for', null);
        })->orderBy('from_id', 'DESC')->pluck('transaction_id', 'from_id');
        $fodder_km = Bookings::find($request->id)->getMeta('fodder_km');
        $fodder_consumption = Bookings::find($request->id)->getMeta('fodder_consumption');
        $next_booking = Bookings::find($request->id)->getMeta('next_booking');
        $index['routed_km'] = empty($fodder_km) ? null : $fodder_km;
        $index['routed_bookid'] = empty($next_booking) ? null : $next_booking;
        $index['routed_consumption'] = empty($fodder_consumption) ? null : $fodder_consumption;
        // dd($index);
        return view('bookings.route', $index);
    }

    public function addroute_save(Request $request)
    {
        // dd($request->all());
        $fodder_km = $request->fodder_km;
        $avg = VehicleModel::find($request->vehicle_id)->getMeta('average');
        $consumption = $fodder_km / $avg;
        $index['fodder_km'] = $fodder_km;
        $index['fodder_consumption'] = $consumption;
        $index['next_booking'] = $request->next_booking;
        $book = Bookings::find($request->book_id);
        $book->setMeta($index);
        $book->save();
        return redirect()->back();
    }

    public function lateDriverAdvance(Request $request)
    {
        // return $request->id;
        $index['booking_id'] = $request->id;
        return view('bookings.late-advance', $index);
    }

    public function lateadvanceSave(Request $request)
    {
        // dd(request()->segment(count($request->segments())));
        // dd($request->all());
        $data = ['advance_pay' => $request->driver_advance, 'advance_date' => $request->advance_date];
        $book = Bookings::find($request->booking_id);
        $book->setMeta($data);
        $book->save();

        //Transaction
        $tranData['from_id'] = $request->booking_id; //Booking ID
        $tranData['type'] = 24; // Debit 
        $tranData['bank_id'] = 1; // Bank ID SELF CASH 
        $tranData['param_id'] = 18; //From Booking
        $tranData['advance_for'] = 21; //Driver Advance in Booking
        $tranData['total'] = $request->driver_advance;

        $transid = Transaction::create($tranData)->id;
        $trash = ['type' => 24, 'from' => 18, 'id' => $transid];
        $transaction_id = Helper::transaction_id($trash);
        Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

        $expense['transaction_id'] = $transid;
        $expense['payment_method'] = 16; //Cash
        $expense['date'] = $request->advance_date;
        $expense['amount'] = $request->driver_advance;
        $expense['remaining'] = 0;
        $expense['remarks'] = $request->remarks;

        IncomeExpense::create($expense);

        // if(request()->segment(count(request()->segments())))
        return redirect()->back();
    }

    public function getMileageDate(Request $request)
    {
        // dd($request->all());
        $fodder_km = $request->fodder_km;
        $avg = VehicleModel::find($request->vehicle_id)->getMeta('average');
        $consumption = $fodder_km / $avg;
        $index['mileage'] = $avg;
        $index['consum'] = $consumption;
        $index['view'] = "<table class='table table-bordered'><tr><th>Mileage</th><td>$avg km</td></tr><tr><th>Consumtion</th><td>$consumption ltr</td></tr></table>";
        return response()->json($index);
    }

    public function modal_save(Request $request)
    {
        // dd($request->all());
        $bookingid = $request->id;
        if (count($request->all()) > 2) {
            // $somearr = ['4'=>'toll_tax','5'=>'food','6'=>'labour','7'=>'advance','8'=>'others'];
            $params = DB::table('params')->where('code', 'AdvanceDriver')->select('label', 'id')->get();
            foreach ($params as $para) {
                $string = str_replace(' ', '_', strtolower($para->label));
                $inputs[$para->id] = $string;
            }
            // dd($request->all(),$inputs,$params);
            // dd($params);
            foreach ($inputs as $key => $arr) {
                if (array_key_exists($arr, $request->remarks)) {
                    // dd(12);
                    $remarks = !empty($request->remarks[$arr]) ? $request->remarks[$arr] : null;
                    // dd($remarks);
                } else $remarks = null;
                $xx = ['booking_id' => $bookingid, 'param_id' => $key, 'value' => $request->$arr, 'remarks' => $remarks];
                // if(!empty($remarks)) dd($xx);
                // dd(123);
                $id = DB::table('advance_driver')->insertGetId($xx);

                // $xx[]['req'] = $key==9 ? $request->$arr : null;
                // Accounting
                if (!empty($request->$arr) && ($key == 7 || $key == 9)) {
                    //setting up the variables
                    if ($key == 7) { //Advance to Driver
                        $book = Bookings::find($bookingid);
                        $date = date("Y-m-d", strtotime($book->pickup));
                        $driver_id = $book->driver_id;
                        $array = ['driver_id' => $driver_id, 'date' => $date, 'amount' => bcdiv($request->$arr, 1, 2), 'advance_driver_id' => $id, 'remarks' => $remarks];
                        $id = DailyAdvance::create($array)->id;

                        $type = 24;
                        $param_id = 25; //salary or daily Advance
                    } else { //Refund
                        $type = 23;
                        $date = date("Y-m-d");
                        $param_id = 27;
                    }

                    $account['from_id'] = $id; //Advance Driver Id
                    $account['bank_id'] = 1; // Bank ID SELF CASH 
                    $account['type'] = $type; //Credit 
                    $account['param_id'] = $param_id; //From Refund [Mark as Complete]
                    $account['advance_for'] = null; //refunded, not advance needed
                    $account['is_complete'] = 1;
                    $account['total'] = bcdiv($request->$arr, 1, 2);


                    $transid = Transaction::create($account)->id;
                    $trash = ['type' => 23, 'from' => $param_id, 'id' => $transid];
                    $transaction_id = Helper::transaction_id($trash);
                    Transaction::find($transid)->update(['transaction_id' => $transaction_id]);

                    $income['transaction_id'] = $transid;
                    $income['payment_method'] = 16;
                    $income['date'] = $date;
                    $income['amount'] = bcdiv($request->$arr, 1, 2);
                    $income['remaining'] = 0;
                    $income['remarks'] = array_key_exists($arr, $request->remarks) ? $request->remarks[$arr] : null;

                    IncomeExpense::create($income);
                }
            }
        }
        // dd($xx);
        $booking = Bookings::find($bookingid);
        $booking->receipt = 1;
        $booking->status = 1;
        $booking->markascomplete_date = date("Y-m-d H:i:s");
        $booking->ride_status = 'Completed';
        $booking->save();

        return redirect()->back();
    }

    public function getOthers(Request $request)
    {
        // dd(1);
        $formData =  $request->all();
        $total = $request->total;
        unset($formData['_token']);
        unset($formData['total']);

        $count = 0;
        foreach ($formData as $k => $v) {
            $vals = $v == 0 || $v == null ? $v = 0 : $v;
            $count += $vals;
        }
        $index['value'] = $total - $count;
        return response()->json($index);
    }

    public function greater(Request $request)
    {
        $response = ['advance' => Helper::greater($request->enter, $request->purse)];
        return response()->json($response);
    }
}
