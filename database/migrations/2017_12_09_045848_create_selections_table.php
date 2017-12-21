<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('selections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('response_id');

            $table->string('txt');

            $table->foreign('response_id')->references('id')->on('responses')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('selections');
    }
}
