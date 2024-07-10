<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToExpenseTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('expense', function (Blueprint $table) {
			$table->index(['vehicle_id', 'exp_id', 'user_id', 'expense_type']);
			$table->index('type');
			$table->index('date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('expense', function (Blueprint $table) {
			$table->dropIndex(['vehicle_id', 'exp_id', 'user_id', 'expense_type']);
			$table->dropIndex(['type']);
			$table->dropIndex(['date']);
		});
	}
}