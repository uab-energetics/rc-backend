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

        foreach (\App\EncodingTask::all() as $task) {
            if ($task->encoding_id === null) {
                continue;
            }
            $publication_id = $task->encoding->publication_id;
            $form_id = $task->encoding->form_id;

            $task->publication_id = $publication_id;
            $task->form_id = $form_id;
            $task->save();

            echo "Updated task $task->id with publication $publication_id and codebook $form_id" . PHP_EOL;
        }
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
