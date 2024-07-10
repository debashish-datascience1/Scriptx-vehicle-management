<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToBookingQuotationTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('booking_quotation', function (Blueprint $table) {
			$table->index(['customer_id', 'user_id', 'vehicle_id', 'driver_id']);
			$table->index(['status', 'payment']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('booking_quotation', function (Blueprint $table) {
			$table->dropIndex(['customer_id', 'user_id', 'vehicle_id', 'driver_id']);
			$table->dropIndex(['status', 'payment']);
		});
	}
}
