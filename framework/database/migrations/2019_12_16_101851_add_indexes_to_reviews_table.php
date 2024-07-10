<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToReviewsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('reviews', function (Blueprint $table) {
			$table->index(['user_id', 'booking_id', 'driver_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('reviews', function (Blueprint $table) {
			$table->dropIndex(['user_id', 'booking_id', 'driver_id']);
		});
	}
}
