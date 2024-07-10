<?php

namespace App\Mail;
use App\Model\Bookings;
use Hyvikk;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VehicleBooked extends Mailable {
	use SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public $booking;
	public function __construct(Bookings $booking) {
		$this->booking = $booking;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->from(Hyvikk::get("email"))->subject('Vehicle Booked. Booking ID: ' . $this->booking->id)->markdown('emails.booked_customer');
	}
}
