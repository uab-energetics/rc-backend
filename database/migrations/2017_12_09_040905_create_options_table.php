<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('question_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');

            $table->string('txt');

            $table->foreign('question_id')->references('id')->on('questions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('question_options');
    }
}
