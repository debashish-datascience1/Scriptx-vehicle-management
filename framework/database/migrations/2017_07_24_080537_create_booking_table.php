<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration {

	public function up() {
		Schema::create('bookings', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('vehicle_id')->nullable();
			$table->integer('driver_id')->nullable();
			$table->timestamp('pickup')->nullable();
			$table->timestamp('dropoff')->nullable();
			$table->integer('duration')->nullable();
			$table->string('pickup_addr')->nullable();
			$table->string('dest_addr')->nullable();
			$table->text('note')->nullable();
			$table->integer('travellers')->default(1);
			$table->integer('status')->default(0);
			$table->integer('payment')->default(0);
			$table->nullableTimestamps();
			$table->softDeletes();

		});
	}

	public function down() {
		Schema::dropIfExists("bookings");
	}
}
