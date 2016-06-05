<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotDefaultProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_question_project', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("default_question_id")->unsigned();
            $table->foreign('default_question_id')
                ->references('id')
                ->on('default_questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer("project_id")->unsigned();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::drop('default_question_project');
    }
}
