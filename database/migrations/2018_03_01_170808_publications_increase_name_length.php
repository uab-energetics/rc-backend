<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublicationsIncreaseNameLength extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('publications', function (Blueprint $table) {
            $table->string('name', 1023)->change();
            $table->string('embedding_url', 1023)->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('publications', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('embedding_url', 255)->change();
        });
    }
}
