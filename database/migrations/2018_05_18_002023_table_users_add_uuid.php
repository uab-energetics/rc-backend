<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUsersAddUuid extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::beginTransaction();

        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid')->default('');
        });

        foreach (\App\User::all() as $user) {
            $user->uuid = strval($user->getKey());
            $user->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid')->unique()->index()->change();
        });
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
