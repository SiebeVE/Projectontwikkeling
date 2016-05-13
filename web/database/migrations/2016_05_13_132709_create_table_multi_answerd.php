<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMultiAnswerd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiple_answerds', function (Blueprint $table) {
            $table->increments('id');

			$table->integer('answer_id')->unsigned();
			$table->foreign('answer_id')
				->references('id')
				->on('answers')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table->integer('possible_answer_id')->unsigned();
			$table->foreign('possible_answer_id')
				->references('id')
				->on('possible_answers')
				->onDelete('cascade')
				->onUpdate('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('multiple_answerds');
    }
}
