<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToBookingIncomeTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('booking_income', function (Blueprint $table) {
			$table->index(['booking_id', 'income_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('booking_income', function (Blueprint $table) {
			$table->dropIndex(['booking_id', 'income_id']);
		});
	}
}
