<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPaymentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('booking_payments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('booking_id')->nullable();
			$table->string('method')->nullable();
			$table->string('transaction_id')->nullable();
			$table->double('amount');
			$table->string('payment_status')->nullable();
			$table->text('payment_details')->nullable();
			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('booking_payments');
	}
}
