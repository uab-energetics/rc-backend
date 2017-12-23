<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableResponsesRanges extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('responses', function (Blueprint $table) {
            $table->double('range_min')->nullable();
            $table->double('range_max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('responses', function (Blueprint $table) {
            $table->dropColumn(['range_min', 'range_max']);
        });
    }
}
