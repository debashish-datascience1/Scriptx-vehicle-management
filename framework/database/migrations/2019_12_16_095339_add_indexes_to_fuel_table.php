<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToFuelTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('fuel', function (Blueprint $table) {
			$table->index(['vehicle_id', 'user_id']);
			$table->index('date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('fuel', function (Blueprint $table) {
			$table->dropIndex(['vehicle_id', 'user_id']);
			$table->dropIndex(['date']);
		});
	}
}
