<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncodingsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('encodings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('publication_id');
            $table->unsignedInteger('form_id');

            $table->string('type');

            $table->foreign('publication_id')->references('id')->on('publications');
            $table->foreign('form_id')->references('id')->on('forms');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('encodings');
    }
}
