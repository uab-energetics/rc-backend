<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableProjectFormAutoEnroll extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('project_form', function (Blueprint $table) {
            $table->boolean('auto_enroll')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('project_form', function (Blueprint $table) {
            $table->dropColumn('auto_enroll');
        });
    }
}
