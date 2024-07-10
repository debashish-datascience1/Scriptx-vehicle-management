<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Bookings;
use Auth;

class AddressController extends Controller {
	public function index() {
		$address = Bookings::where('customer_id', Auth::user()->id)->get();
		$bookings = Address::where('customer_id', Auth::user()->id)->get();

		return view('customers.address', compact('bookings'));
	}
}
