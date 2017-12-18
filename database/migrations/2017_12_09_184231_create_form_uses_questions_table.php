<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormUsesQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_question', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('category_id');

            $table->foreign('form_id')->references('id')->on('forms')
                ->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')
                ->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade');

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
        Schema::dropIfExists('form_question');
    }
}
