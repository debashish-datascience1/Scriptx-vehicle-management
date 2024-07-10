<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxesToIncomeTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('income', function (Blueprint $table) {
			$table->double('tax_percent', 10, 2)->nullable();
			$table->double('tax_charge_rs', 10, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('income', function (Blueprint $table) {
			$table->dropColumn('tax_percent');
			$table->dropColumn('tax_charge_rs');
		});
	}
}
