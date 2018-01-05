<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableFormPublications extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('form_publication', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_form_id');
            $table->unsignedInteger('publication_id');

            $table->integer('priority')->default(0);

            $table->foreign('project_form_id')->references('id')->on('project_form')
                ->onDelete('cascade');
            $table->foreign('publication_id')->references('id')->on('publications')
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
        Schema::dropIfExists('form_publication');
    }
}
