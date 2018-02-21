<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableEncodingsCascadingDeletes extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('encodings', function (Blueprint $table) {
            $table->dropForeign('encodings_publication_id_foreign');
            $table->dropForeign('encodings_form_id_foreign');

            $table->foreign('publication_id')->references('id')->on('publications')
                ->onDelete('cascade');
            $table->foreign('form_id')->references('id')->on('forms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('encodings', function (Blueprint $table) {
            $table->dropForeign('encodings_publication_id_foreign');
            $table->dropForeign('encodings_form_id_foreign');

            $table->foreign('publication_id')->references('id')->on('publications');
            $table->foreign('form_id')->references('id')->on('forms');
        });
    }
}
