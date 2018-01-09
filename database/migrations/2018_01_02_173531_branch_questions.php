<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BranchQuestions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('branch_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('question_id');
            $table->unsignedInteger('branch_id');

            $table->foreign('question_id')->references('id')->on('questions')
                ->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('encoding_experiment_branches')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('branch_questions');
    }
}
