<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToVendors extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('vendors', function (Blueprint $table) {
			$table->string('country')->nullable();
			$table->string('postal_code')->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('vendors', function (Blueprint $table) {
			$table->dropColumn('country');
			$table->dropColumn('postal_code');

		});
	}
}
