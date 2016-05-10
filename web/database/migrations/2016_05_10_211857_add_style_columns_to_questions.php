<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStyleColumnsToQuestions extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('questions', function (Blueprint $table)
		{
			$table->string("leftOffset")->after("question");
			$table->string("topOffset")->after("leftOffset");
			$table->integer("width")->after("topOffset")->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('questions', function (Blueprint $table)
		{
			$table->removeColumn([
				'leftOffset',
				'topOffset',
				'width'
			]);
		});
	}
}
