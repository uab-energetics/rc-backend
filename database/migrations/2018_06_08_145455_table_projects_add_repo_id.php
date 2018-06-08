<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableProjectsAddRepoId extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('repo_uuid', 255)->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('repo_uuid');
        });
    }
}
