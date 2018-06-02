<?php

use App\Models\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableResponsesIncreaseTextLength extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('responses', function (Blueprint $table) {
            $table->text('txt')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('responses', function (Blueprint $table) {
            $responses = Response::query()
                ->where('type', '=', RESPONSE_TEXT)
                ->where('txt', '!=', null)
                ->get();
            foreach ($responses as $response) {
                $response->txt = substr($response->txt, 0, 185);
                $response->save();
            }
            $table->string('txt')->change();
        });
    }
}
