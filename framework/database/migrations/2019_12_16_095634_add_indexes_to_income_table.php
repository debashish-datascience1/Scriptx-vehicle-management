<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToIncomeTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('income', function (Blueprint $table) {
			$table->index(['vehicle_id', 'income_id', 'user_id', 'income_cat']);
			$table->index(['date']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('income', function (Blueprint $table) {
			$table->dropIndex(['vehicle_id', 'income_id', 'user_id', 'income_cat']);
			$table->dropIndex(['date']);
		});
	}
}