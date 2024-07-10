<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingQuotationTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('booking_quotation', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('customer_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('vehicle_id')->nullable();
			$table->integer('driver_id')->nullable();
			$table->timestamp('pickup')->nullable();
			$table->timestamp('dropoff')->nullable();
			$table->string('pickup_addr')->nullable();
			$table->string('dest_addr')->nullable();
			$table->text('note')->nullable();
			$table->integer('travellers')->default(1);
			$table->integer('status')->default(0);
			$table->integer('payment')->default(0);
			$table->integer('day')->nullable();
			$table->double('mileage')->nullable();
			$table->integer('waiting_time')->nullable();
			$table->double('total')->nullable();
			$table->nullableTimestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('booking_quotation');
	}
}
