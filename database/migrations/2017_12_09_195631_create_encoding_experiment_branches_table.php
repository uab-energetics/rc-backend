<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncodingExperimentBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encoding_experiment_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('encoding_id')->unsigned();
            $table->foreign('encoding_id')->references('id')->on('encodings');
            $table->string('name')->nullable();
            $table->string('desc')->nullable();
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
        Schema::dropIfExists('encoding_experiment_branches');
    }
}
