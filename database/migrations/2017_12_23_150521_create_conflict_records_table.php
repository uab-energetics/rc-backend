<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConflictRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conflict_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('encoding_id');
            $table->unsignedInteger('other_encoding_id');
            $table->unsignedInteger('question_id');

            $table->boolean('agrees');
            $table->string('message')->nullable();

            $table->foreign('encoding_id')->references('id')->on('encodings')
                ->onDelete('cascade');
            $table->foreign('other_encoding_id')->references('id')->on('encodings')
                ->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')
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
        Schema::dropIfExists('conflict_records');
    }
}
