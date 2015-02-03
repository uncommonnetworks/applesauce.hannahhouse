<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bedstatushistory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bed_status_history', function($table) {

			$table->create();

			$table->integer('bed_id')->unsigned();
			$table->integer('resident_id')->unsigned()->nullable();
			$table->string('status')->length(30);
			$table->datetime('start_date');
			$table->integer('updated_by')->unsigned();



			$table->foreign('bed_id')->references('id')->on('bed');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('resident_id')->references('id')->on('residents');


			$table->index('bed_id');
			$table->index('start_date');
			$table->index('bed_id','start_date');
//			$table->unique('bed_id','start_date');


			$table->index('updated_by');
});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::drop('bed_status_history');
	}

}
