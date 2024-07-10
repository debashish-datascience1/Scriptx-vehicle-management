<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToFareSettingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('fare_settings', function (Blueprint $table) {
			$table->index('key_name');
			$table->index('type_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('fare_settings', function (Blueprint $table) {
			$table->dropIndex(['key_name']);
			$table->dropIndex(['type_id']);
		});
	}
}
