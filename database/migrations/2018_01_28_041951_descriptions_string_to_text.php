<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DescriptionsStringToText extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::beginTransaction();

        Schema::table('forms', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::beginTransaction();

//        Schema::table('forms', function (Blueprint $table) {
//            $table->string('description')->nullable()->change();
//        });
//
//        Schema::table('categories', function (Blueprint $table) {
//            $table->string('description')->nullable()->change();
//        });
//
//        Schema::table('questions', function (Blueprint $table) {
//            $table->string('description')->nullable()->change();
//        });

        DB::commit();
    }
}
