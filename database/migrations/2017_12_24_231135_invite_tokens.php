<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InviteTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_invite_tokens', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('project_id');
            $table->string('token_key');
            $table->string('access_level');
            $table->dateTime('expires');

            $table->foreign('creator_id')->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('project_id')->references('id')->on('projects')
                ->onDelete('cascade');

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
        Schema::dropIfExists('project_invite_tokens');
    }
}
