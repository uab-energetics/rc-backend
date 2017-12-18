<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimpleEncodingResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encoding_simple_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('encoding_id')->unsigned();
            $table->foreign('encoding_id')->references('id')->on('encodings');
            $table->integer('response_id')->unsigned();
            $table->foreign('response_id')->references('id')->on('responses');
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
        Schema::dropIfExists('encoding_simple_responses');
    }
}
