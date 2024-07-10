<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class BookingPaymentsModel extends Model {
	protected $table = "booking_payments";
	protected $fillable = ['booking_id', 'method', 'amount', 'payment_details', 'transaction_id', 'payment_status'];
}