<?php

namespace App\Mail;
use Hyvikk;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewRegistration extends Mailable {
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public $vehicle;
	public $reg_date;
	public $user;
	public function __construct($vehicle, $reg_date, $user) {
		$this->vehicle = $vehicle;
		$this->reg_date = $reg_date;
		$this->user = $user;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->from(Hyvikk::get("email"))->subject('Renew Vehicle Registration')->markdown('emails.renew_registration');
	}
}
