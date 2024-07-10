<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToServiceReminderTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('service_reminder', function (Blueprint $table) {
			$table->index(['vehicle_id', 'service_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('service_reminder', function (Blueprint $table) {
			$table->dropIndex(['vehicle_id', 'service_id']);
		});
	}
}