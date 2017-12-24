<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersBio extends Migration
{
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
            $table->text('bio')->nullable();
            $table->text('website')->nullable();
            $table->text('department')->nullable();
            $table->string('theme', 64);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bio');
            $table->dropColumn('website');
            $table->dropColumn('department');
            $table->dropColumn('theme');
        });
    }
}
