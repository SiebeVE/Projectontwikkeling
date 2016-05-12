<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePossibleAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('possible_answers', function (Blueprint $table) {
            $table->increments('id');

			$table->integer('question_id')->unsigned();
			$table->foreign('question_id')
				->references('id')
				->on('questions')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table->string('answer');
            
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
        Schema::drop('possible_answers');
    }
}
