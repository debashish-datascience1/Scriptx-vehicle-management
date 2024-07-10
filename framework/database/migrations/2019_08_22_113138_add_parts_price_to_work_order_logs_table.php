<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartsPriceToWorkOrderLogsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('work_order_logs', function (Blueprint $table) {
			$table->double('parts_price')->default(0)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('work_order_logs', function (Blueprint $table) {
			$table->dropColumn('parts_price');

		});
	}
}
