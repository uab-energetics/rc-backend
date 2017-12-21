<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableExperimentBranchesDescription extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('encoding_experiment_branches', function (Blueprint $table) {
            $table->renameColumn('desc', 'description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('encoding_experiment_branches', function (Blueprint $table) {
            $table->renameColumn('description', 'desc');
        });
    }
}
