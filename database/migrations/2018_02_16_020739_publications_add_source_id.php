<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublicationsAddSourceId extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::beginTransaction();
        Schema::table('publications', function (Blueprint $table) {
            $table->string('source_id')->nullable();
        });

        foreach (\App\Publication::all() as $publication) {
            $pmid = $this->tryParsePMC($publication->embedding_url);
            if ($pmid === null) {
                continue;
            }
            $publication->source_id = "PMC" . $pmid;
            echo $publication->source_id . PHP_EOL;
            $publication->save();
        }

        DB::commit();
    }

    private function tryParsePMC($embed_url) {
        $expressions = ["/pubmed\/(\d+)/", "/PMC(\d+)/"];

        foreach ($expressions as $expr) {
            $matches = [];
            $res = preg_match($expr, $embed_url, $matches);
            if ($res === 1) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn('source_id');
        });
    }
}
