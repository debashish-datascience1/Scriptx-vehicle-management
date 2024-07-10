<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookingsMeta extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('bookings_meta', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('booking_id')->unsigned()->index();
			$table->string('type')->default('null');

			$table->string('key')->index();
			$table->text('value')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('bookings_meta');
	}
}
