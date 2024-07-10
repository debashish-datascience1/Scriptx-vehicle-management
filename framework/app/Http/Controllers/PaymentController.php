<?php

namespace App\Http\Controllers;
use App\Model\BookingPaymentsModel;
use App\Model\Bookings;
use Exception;
use Hyvikk;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller {

	// public function redirect_payment(Request $request) {
	// 	if ($request->method == "cash") {
	// 		return redirect('cash/' . $request->booking_id);
	// 	}
	// 	if ($request->method == "stripe") {
	// 		return redirect('stripe/' . $request->booking_id);
	// 	}
	// 	if ($request->method == "razorpay") {
	// 		return redirect('razorpay/' . $request->booking_id);
	// 	}
	// }

	public function cash($booking_id) {
		$booking = Bookings::find($booking_id);
		$booking->payment = 1;
		$booking->payment_method = "cash";
		$booking->status = 1;
		$booking->ride_status = "Completed";
		$booking->save();

		BookingPaymentsModel::create(['method' => 'cash', 'booking_id' => $booking_id, 'amount' => $booking->tax_total, 'payment_details' => null, 'transaction_id' => null, 'payment_status' => "succeeded"]);
		$data['amount'] = $booking->tax_total;
		return view('payments.payment_success', $data);
	}

	public function stripe($booking_id) {
		try {
			$booking = Bookings::find($booking_id);
			\Stripe\Stripe::setApiKey(Hyvikk::payment('stripe_secret_key'));
			$methods = ['card'];
			if (Hyvikk::payment('currency_code') == "EUR") {
				$methods = ['card', 'ideal'];
			}
			$session = \Stripe\Checkout\Session::create([
				'payment_method_types' => $methods,
				'line_items' => [[
					'name' => 'Booking',
					'amount' => $booking->tax_total * 100,
					'currency' => strtolower(Hyvikk::payment('currency_code')),
					'quantity' => 1,
				]],
				'payment_intent_data' => [
					'capture_method' => 'automatic',
				],
				'success_url' => url('stripe-success') . "?session_id={CHECKOUT_SESSION_ID}&booking_id=" . $booking_id,
				'cancel_url' => url('stripe-cancel'),
			]);
			$session_id = $session['id'];
			return view('payments.stripe', compact('session_id'));
		} catch (\Stripe\Exception\CardException $e) {
			// Since it's a decline, \Stripe\Exception\CardException will be caught
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));

		} catch (\Stripe\Exception\RateLimitException $e) {
			// Too many requests made to the API too quickly
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));

		} catch (\Stripe\Exception\InvalidRequestException $e) {
			// Invalid parameters were supplied to Stripe's API
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));

		} catch (\Stripe\Exception\AuthenticationException $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));

		} catch (\Stripe\Exception\ApiConnectionException $e) {
			// Network communication with Stripe failed
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));

		} catch (\Stripe\Exception\ApiErrorException $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));

		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$error_msg = $e->getError()->message;
			return view('payments.payment_failed', compact('error_msg'));
		}
	}

	public function stripe_success() {
		$booking_id = $_GET['booking_id'];
		\Stripe\Stripe::setApiKey(Hyvikk::payment('stripe_secret_key'));
		$payment_data = \Stripe\Checkout\Session::retrieve(
			$_GET['session_id']
		);
		// dd($payment_data);
		$payment_int = \Stripe\PaymentIntent::retrieve(
			$payment_data['payment_intent']
		);
		// dd($payment_int->charges->data[0]->payment_method_details->ideal);
		// dd($payment_int['charges']['data'][0]['id']);

		/*
			if ($payment_int->charges->data[0]->payment_method_details->type == "card") {
				$payment_method_details = array(
					'card' => array(
						'brand' => $payment_int->charges->data[0]->payment_method_details->card->brand,
						'country' => $payment_int->charges->data[0]->payment_method_details->card->country,
						'exp_month' => $payment_int->charges->data[0]->payment_method_details->card->exp_month,
						'exp_year' => $payment_int->charges->data[0]->payment_method_details->card->exp_year,
						'fingerprint' => $payment_int->charges->data[0]->payment_method_details->card->fingerprint,
						'funding' => $payment_int->charges->data[0]->payment_method_details->card->funding,
						'installments' => $payment_int->charges->data[0]->payment_method_details->card->installments,
						'last4' => $payment_int->charges->data[0]->payment_method_details->card->last4,
						'network' => $payment_int->charges->data[0]->payment_method_details->card->network,
						'three_d_secure' => $payment_int->charges->data[0]->payment_method_details->card->three_d_secure,
						'wallet' => $payment_int->charges->data[0]->payment_method_details->card->wallet,
					),
				);
			}
			if ($payment_int->charges->data[0]->payment_method_details->type == "ideal") {
				$payment_method_details = array(
					"bank" => $payment_int->charges->data[0]->payment_method_details->ideal->bank,
					"bic" => $payment_int->charges->data[0]->payment_method_details->ideal->bic,
					"iban_last4" => $payment_int->charges->data[0]->payment_method_details->ideal->iban_last4,
					"verified_name" => $payment_int->charges->data[0]->payment_method_details->ideal->verified_name,
				);
			}
		*/
		$info = array(
			'charge_id' => $payment_int['charges']['data'][0]['id'],
			'session_id' => $_GET['session_id'],
			'payment_intent_id' => $payment_data['payment_intent'],
			'payment_method_details' => $payment_int->charges->data[0]->payment_method_details,
		);
		$booking = Bookings::find($booking_id);

		BookingPaymentsModel::create(['method' => 'stripe', 'booking_id' => $booking_id, 'amount' => $booking->tax_total, 'payment_details' => json_encode($info), 'transaction_id' => $payment_int['charges']['data'][0]['balance_transaction'], 'payment_status' => $payment_int['charges']['data'][0]['status']]);
		$booking->payment = 1;
		$booking->payment_method = "stripe";
		$booking->status = 1;
		$booking->ride_status = "Completed";
		$booking->save();
		// dd($payment_int['charges']['data']);
		$data['amount'] = $booking->tax_total;
		return view('payments.payment_success', $data);
	}

	public function stripe_cancel() {
		$error_msg = "You have cancelled payment transaction successfully!";
		return view('payments.payment_failed', compact('error_msg'));
	}

	public function razorpay($booking_id) {
		try {
			$booking = Bookings::find($booking_id);
			$receipt_no = time() . "_" . date('Y_m_d') . "_" . $booking_id;
			$api = new Api(Hyvikk::payment('razorpay_key'), Hyvikk::payment('razorpay_secret'));
			$order = $api->order->create(array('receipt' => $receipt_no, 'amount' => $booking->tax_total * 100, 'currency' => Hyvikk::payment('currency_code'), 'payment_capture' => 1));
			// dd($order);
			$data['order_id'] = $order['id'];
			$data['amount'] = $booking->tax_total * 100;
			$data['booking_id'] = $booking_id;
			return view('payments.razorpay_form', $data);
		} catch (Exception $e) {
			$error_msg = $e->getMessage();
			return view('payments.payment_failed', compact('error_msg'));
		}
	}

	public function razorpay_success(Request $request) {
		if ($request->error) {
			$error_msg = $request->error['description'];
			return view('payments.payment_failed', compact('error_msg'));
		} else {
			$api = new Api(Hyvikk::payment('razorpay_key'), Hyvikk::payment('razorpay_secret'));
			$payment = $api->payment->fetch($request->razorpay_payment_id);
			// dd($payment);
			// $order = $api->order->fetch($request->razorpay_order_id);
			// dd($order);
			$payment_info = array(
				"razorpay_payment_id" => $payment->id,
				"razorpay_order_id" => $request->razorpay_order_id,
				"razorpay_signature" => $request->razorpay_signature,
				"entity" => $payment->entity,
				"amount" => $payment->amount,
				"currency" => $payment->currency,
				"status" => $payment->status,
				"order_id" => $payment->order_id,
				"invoice_id" => $payment->invoice_id,
				"international" => $payment->international,
				"method" => $payment->method,
				"amount_refunded" => $payment->amount_refunded,
				"refund_status" => $payment->refund_status,
				"captured" => $payment->captured,
				"description" => $payment->description,
				"card_id" => $payment->card_id,
				"bank" => $payment->bank,
				"wallet" => $payment->wallet,
				"vpa" => $payment->vpa,
				"email" => $payment->email,
				"contact" => $payment->contact,
				"fee" => $payment->fee,
				"tax" => $payment->tax,
				"error_code" => $payment->error_code,
				"error_description" => $payment->error_description,
				"created_at" => $payment->created_at,
			);
			$booking_id = $_GET['booking_id'];
			$booking = Bookings::find($booking_id);
			BookingPaymentsModel::create(['method' => 'razorpay', 'booking_id' => $booking_id, 'amount' => $booking->tax_total, 'payment_details' => json_encode($payment_info), 'transaction_id' => $payment['id'], 'payment_status' => "succeeded"]);
			$booking->payment = 1;
			$booking->payment_method = "razorpay";
			$booking->status = 1;
			$booking->ride_status = "Completed";
			$booking->save();
			$data['amount'] = $booking->tax_total;
			return view('payments.payment_success', $data);
		}
	}

	public function razorpay_failed() {
		$error_msg = "You have cancelled payment transaction successfully!";
		return view('payments.payment_failed', compact('error_msg'));
	}
}