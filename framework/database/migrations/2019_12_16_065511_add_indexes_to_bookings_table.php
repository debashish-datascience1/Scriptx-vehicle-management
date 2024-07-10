<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToBookingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('bookings', function (Blueprint $table) {
			$table->index(['customer_id', 'driver_id', 'vehicle_id', 'user_id']);
			$table->index(['payment', 'status']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('bookings', function (Blueprint $table) {
			$table->dropIndex(['customer_id', 'driver_id', 'vehicle_id', 'user_id']);
			$table->dropIndex(['payment', 'status']);
		});
	}
}