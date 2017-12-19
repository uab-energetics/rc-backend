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
            $table->unsignedInteger('encoding_id');
            $table->unsignedInteger('response_id');

            $table->foreign('encoding_id')->references('id')->on('encodings')
                ->onDelete('cascade');
            $table->foreign('response_id')->references('id')->on('responses')
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
        Schema::dropIfExists('encoding_simple_responses');
    }
}
