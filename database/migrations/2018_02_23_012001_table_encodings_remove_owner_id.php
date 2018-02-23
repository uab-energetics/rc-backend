<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableEncodingsRemoveOwnerId extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('encodings', function (Blueprint $table) {
            $table->dropForeign('encodings_owner_id_foreign');
            $table->dropColumn('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('encodings', function (Blueprint $table) {
            $table->unsignedInteger('owner_id')->nullable();


            $table->foreign('owner_id')->references('id')->on('users')
                ->onDelete('set null');
        });
    }
}
