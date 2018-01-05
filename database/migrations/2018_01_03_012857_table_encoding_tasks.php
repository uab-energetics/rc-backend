<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableEncodingTasks extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('encoding_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('encoding_id')->nullable();
            $table->unsignedInteger('project_form_id');
            $table->unsignedInteger('encoder_id');

            $table->boolean('active')->default(true);
            $table->boolean('complete')->default(false);

            $table->foreign('encoding_id')->references('id')->on('encodings')
                ->onDelete('set null');
            $table->foreign('project_form_id')->references('id')->on('project_form')
                ->onDelete('cascade');
            $table->foreign('encoder_id')->references('id')->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('encoding_tasks');
    }
}
