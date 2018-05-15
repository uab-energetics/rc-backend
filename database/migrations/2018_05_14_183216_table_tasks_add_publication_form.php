<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableTasksAddPublicationForm extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('encoding_tasks', function (Blueprint $table) {
            $table->unsignedInteger('publication_id')->nullable();
            $table->unsignedInteger('form_id')->nullable();


            $table->foreign('publication_id')->references('id')->on('publications')
                ->onDelete('set null');
            $table->foreign('form_id')->references('id')->on('forms')
                ->onDelete('set null');

        });

        echo "Updating tasks. This runs in O(tasks) time." . PHP_EOL;
        $deleted = 0;
        $updated = 0;
        foreach (\App\EncodingTask::all() as $task) {
            if ( ($deleted + $updated) % 50 === 0 ) {
                echo "\tprocessed ". ($deleted + $updated) . " tasks" . PHP_EOL;
            }
            if ($task->encoding_id === null) {
                $deleted++;
                $task->delete();
                continue;
            }
            $encoding = \App\Encoding::find($task->encoding_id);
            $publication_id = $encoding->publication_id;
            $form_id = $encoding->form_id;

            $task->publication_id = $publication_id;
            $task->form_id = $form_id;
            $task->save();

            $updated++;
        }
        echo "Updated $updated and deleted $deleted encoding tasks" . PHP_EOL;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('encoding_tasks', function (Blueprint $table) {
            $table->dropForeign('encoding_tasks_publication_id_foreign');
            $table->dropForeign('encoding_tasks_form_id_foreign');

            $table->dropColumn('form_id');
            $table->dropColumn('publication_id');
        });
    }
}
