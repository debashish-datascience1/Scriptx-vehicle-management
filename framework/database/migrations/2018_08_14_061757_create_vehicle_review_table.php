<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleReviewTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('vehicle_review', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('vehicle_id');
			$table->integer('user_id')->nullable();
			$table->string('reg_no')->nullable();
			$table->integer('kms_outgoing')->nullable();
			$table->integer('kms_incoming')->nullable();
			$table->integer('fuel_level_out')->nullable();
			$table->integer('fuel_level_in')->nullable();
			$table->datetime('datetime_outgoing')->nullable();
			$table->datetime('datetime_incoming')->nullable();
			$table->text('petrol_card')->nullable();
			$table->text('lights')->nullable();
			$table->text('invertor')->nullable();
			$table->text('car_mats')->nullable();
			$table->text('int_damage')->nullable();
			$table->text('int_lights')->nullable();
			$table->text('ext_car')->nullable();
			$table->text('tyre')->nullable();
			$table->text('ladder')->nullable();
			$table->text('leed')->nullable();
			$table->text('power_tool')->nullable();
			$table->text('ac')->nullable();
			$table->text('head_light')->nullable();
			$table->text('lock')->nullable();
			$table->text('windows')->nullable();
			$table->text('condition')->nullable();
			$table->text('oil_chk')->nullable();
			$table->text('suspension')->nullable();
			$table->text('tool_box')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('vehicle_review');
	}
}
