<?php

namespace App\Mail;

use Hyvikk;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewVehicleLicence extends Mailable {
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public $vehicle;
	public $lic_date;
	public $user;
	public function __construct($vehicle, $lic_date, $user) {
		$this->vehicle = $vehicle;
		$this->lic_date = $lic_date;
		$this->user = $user;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->from(Hyvikk::get("email"))->subject('Renew Vehicle Licence')->markdown('emails.renew_vehicle_licence');
	}
}
