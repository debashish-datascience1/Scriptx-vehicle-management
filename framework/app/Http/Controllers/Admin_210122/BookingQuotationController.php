<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingQuotationRequest;
use App\Mail\DriverBooked;
use App\Mail\VehicleBooked;
use App\Model\Address;
use App\Model\BookingIncome;
use App\Model\BookingQuotationModel;
use App\Model\Bookings;
use App\Model\Hyvikk;
use App\Model\IncCats;
use App\Model\IncomeModel;
use App\Model\User;
use App\Model\VehicleModel;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PushNotification;

class BookingQuotationController extends Controller
{
    public function index()
    {
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $vehicle_ids = VehicleModel::pluck('id')->toArray();
        } else {
            $vehicle_ids = VehicleModel::where('group_id', Auth::user()->group_id)->pluck('id')->toArray();
        }
        $data = BookingQuotationModel::whereIn('vehicle_id', $vehicle_ids)->orderBy('id', 'desc')->get();
        $types = IncCats::get();
        return view('booking_quotation.index', compact('data', 'types'));
    }

    public function create()
    {
        $group_id = Auth::user()->group_id;
        $data['customers'] = User::where('user_type', 'C')->get();
        $data['drivers'] = User::whereUser_type("D")->get();
        $data['addresses'] = Address::where('customer_id', Auth::user()->id)->get();
        if ($group_id == null || Auth::user()->user_type == "S") {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where([['group_id', $group_id], ['in_service', '1']])->get();
        }
        return view('booking_quotation.create', $data);
    }

    public function store(BookingQuotationRequest $request)
    {
        // dd($request->all());
        $xx = $this->check_booking($request->get("pickup"), $request->get("dropoff"), $request->get("vehicle_id"));
        if ($xx) {
            $id = BookingQuotationModel::create($request->all())->id;

            Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('pickup_addr')]);

            Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('dest_addr')]);

            return redirect()->route("booking-quotation.index");
        } else {
            return redirect()->route("booking-quotation.create")->withErrors(["error" => "Selected Vehicle is not Available in Given Timeframe"])->withInput();
        }
    }

    public function edit($id)
    {
        $group_id = Auth::user()->group_id;
        $data['customers'] = User::where('user_type', 'C')->get();
        $data['drivers'] = User::whereUser_type("D")->get();
        $data['addresses'] = Address::where('customer_id', Auth::user()->id)->get();
        if ($group_id == null || Auth::user()->user_type == "S") {
            $data['vehicles'] = VehicleModel::whereIn_service("1")->get();
        } else {
            $data['vehicles'] = VehicleModel::where([['group_id', $group_id], ['in_service', '1']])->get();
        }
        $data['data'] = BookingQuotationModel::find($id);
        return view('booking_quotation.edit', $data);
    }

    public function update(BookingQuotationRequest $request)
    {
        // dd($request->all());
        $form_data = $request->all();
        unset($form_data['_method']);
        unset($form_data['_token']);
        $xx = $this->check_booking($request->get("pickup"), $request->get("dropoff"), $request->get("vehicle_id"));
        if ($xx) {
            BookingQuotationModel::where('id', $request->id)->update($form_data);
            return redirect('admin/booking-quotation');
        } else {
            return back()->withErrors(["error" => "Selected Vehicle is not Available in Given Timeframe"])->withInput();
        }
    }

    public function destroy(Request $request)
    {
        BookingQuotationModel::find($request->id)->delete();
        return back();
    }

    protected function check_booking($pickup, $dropoff, $vehicle)
    {
        $chk = Bookings::where("status", 0)->where('vehicle_id', $vehicle)->whereBetween('pickup', [$pickup, $dropoff])->orWhereBetween('dropoff', [$pickup, $dropoff])->get();

        if (count($chk) > 0) {
            return false;
        } else {
            return true;
        }

    }

    public function invoice($id)
    {
        $data = BookingQuotationModel::find($id);
        // dd($quote);
        return view('booking_quotation.receipt', compact('data'));
    }

    function print($id) {
        $data = BookingQuotationModel::find($id);
        return view('booking_quotation.print', compact('data'));
    }

    public function approve($id)
    {
        $group_id = Auth::user()->group_id;
        $data['customers'] = User::where('user_type', 'C')->get();
        $data['drivers'] = User::whereUser_type("D")->get();
        $data['addresses'] = Address::where('customer_id', Auth::user()->id)->get();
        $data['data'] = BookingQuotationModel::find($id);
        if (Auth::user()->group_id == null || Auth::user()->user_type == "S") {
            $data['vehicles'] = VehicleModel::whereRaw("in_service=1 and deleted_at is null and id not in (select vehicle_id from bookings where status=0 and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $data['data']->pickup . "' and '" . $data['data']->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $data['data']->pickup . "' and '" . $data['data']->dropoff . "'  or dropoff between '" . $data['data']->pickup . "' and '" . $data['data']->dropoff . "'))")->get();
        } else {
            $data['vehicles'] = VehicleModel::whereRaw("in_service=1 and deleted_at is null and group_id=" . Auth::user()->group_id . " and id not in (select vehicle_id from bookings where status=0 and deleted_at is null and  (DATE_SUB(pickup, INTERVAL 15 MINUTE) between '" . $data['data']->pickup . "' and '" . $data['data']->dropoff . "' or DATE_ADD(dropoff, INTERVAL 15 MINUTE) between '" . $data['data']->pickup . "' and '" . $data['data']->dropoff . "'  or dropoff between '" . $data['data']->pickup . "' and '" . $data['data']->dropoff . "'))")->get();
        }

        return view('booking_quotation.approve', $data);
    }

    public function add_booking(BookingQuotationRequest $request)
    {
        // dd($request->all());
        $xx = $this->check_booking($request->get("pickup"), $request->get("dropoff"), $request->get("vehicle_id"));
        if ($xx) {
            $id = Bookings::create($request->all())->id;

            Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('pickup_addr')]);

            Address::updateOrCreate(['customer_id' => $request->get('customer_id'), 'address' => $request->get('dest_addr')]);

            $booking = Bookings::find($id);
            $booking->user_id = $request->get("user_id");
            $booking->driver_id = $request->get('driver_id');
            $dropoff = Carbon::parse($booking->dropoff);
            $pickup = Carbon::parse($booking->pickup);
            $diff = $pickup->diffInMinutes($dropoff);
            $booking->note = $request->get('note');
            $booking->duration = $diff;
            $booking->accept_status = 1; //0=yet to accept, 1= accept
            $booking->ride_status = "Upcoming";
            $booking->booking_type = 1;
            $booking->journey_date = date('d-m-Y', strtotime($booking->pickup));
            $booking->journey_time = date('H:i:s', strtotime($booking->pickup));
            $booking->receipt = 1;
            $booking->day = $request->get('day');
            $booking->mileage = $request->get('mileage');
            $booking->waiting_time = $request->get('waiting_time');
            $booking->date = date('Y-m-d');
            $booking->total = $request->get('total');
            $booking->total_kms = $request->get('mileage');
            $booking->tax_total = $request->get('tax_total');
            $booking->total_tax_percent = $request->get('total_tax_percent');
            $booking->total_tax_charge_rs = $request->total_tax_charge_rs;
            $booking->save();

            $inc_id = IncomeModel::create([
                "vehicle_id" => $request->get("vehicle_id"),
                // "amount" => $request->get('total'),
                "amount" => $request->get('tax_total'),
                "user_id" => $request->get("customer_id"),
                "date" => date('Y-m-d'),
                "mileage" => $request->get("mileage"),
                "income_cat" => 1,
                "income_id" => $booking->id,
                "tax_percent" => $request->get('total_tax_percent'),
                "tax_charge_rs" => $request->total_tax_charge_rs,
            ])->id;

            BookingIncome::create(['booking_id' => $booking->id, "income_id" => $inc_id]);

            $this->booking_notification($booking->id);
            if (Hyvikk::email_msg('email') == 1) {
                Mail::to($booking->customer->email)->send(new VehicleBooked($booking));
                Mail::to($booking->driver->email)->send(new DriverBooked($booking));
            }
            // browser notification to driver,admin,customer
            BookingQuotationModel::find($request->id)->delete();
            return redirect('admin/booking-quotation')->with('msg', 'Booking quotation approved successfully and added to bookings.');
        } else {
            return back()->withErrors(["error" => "Selected Vehicle is not Available in Given Timeframe"])->withInput();
        }
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
        $data['data'] = array('rideinfo' => array(

            'booking_id' => $booking->id,
            'source_address' => $booking->pickup_addr,
            'dest_address' => $booking->dest_addr,
            'book_timestamp' => date('Y-m-d H:i:s', strtotime($booking->created_at)),
            'ridestart_timestamp' => null,
            'journey_date' => date('d-m-Y', strtotime($booking->pickup)),
            'journey_time' => date('H:i:s', strtotime($booking->pickup)),
            'ride_status' => "Upcoming"),
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
        BookingQuotationModel::whereIn('id', $request->ids)->delete();
        return back();
    }
}
