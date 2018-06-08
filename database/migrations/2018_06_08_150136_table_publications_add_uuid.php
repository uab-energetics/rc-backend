<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablePublicationsAddUuid extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::beginTransaction();

        Schema::table('publications', function (Blueprint $table) {
            $table->string('uuid', 255)->nullable();
        });

        foreach (\App\Publication::all() as $publication) {
            $publication->uuid = \Ramsey\Uuid\Uuid::uuid1();
            $publication->save();
        }

        Schema::table('publications', function (Blueprint $table) {
            $table->string('uuid', 255)->index()->change();
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
