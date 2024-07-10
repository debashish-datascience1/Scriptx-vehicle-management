<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToVehiclesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('vehicles', function (Blueprint $table) {
			$table->index(['group_id', 'type_id', 'user_id', 'in_service']);
			$table->index(['lic_exp_date', 'reg_exp_date']);
			$table->index(['license_plate']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('vehicles', function (Blueprint $table) {
			$table->dropIndex(['group_id', 'type_id', 'user_id', 'in_service']);
			$table->dropIndex(['lic_exp_date', 'reg_exp_date']);
			$table->dropIndex(['license_plate']);
		});
	}
}