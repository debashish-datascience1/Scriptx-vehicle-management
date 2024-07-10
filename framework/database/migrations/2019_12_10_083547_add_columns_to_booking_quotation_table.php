<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBookingQuotationTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('booking_quotation', function (Blueprint $table) {
			$table->double('tax_total', 10, 2)->nullable();
			$table->double('total_tax_percent', 10, 2)->nullable();
			$table->double('total_tax_charge_rs', 10, 2)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('booking_quotation', function (Blueprint $table) {
			$table->dropColumn('tax_total');
			$table->dropColumn('total_tax_percent');
			$table->dropColumn('total_tax_charge_rs');
		});
	}
}
