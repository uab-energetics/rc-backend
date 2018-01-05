<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncodingTask extends UniqueJunction {
    protected $table = "encoding_tasks";

    protected $fillable = ['project_form_id', 'encoder_id', 'encoding_id', 'active', 'complete'];

    public function encoding() {
        return $this->belongsTo(Encoding::class, 'encoding_id');
    }

    public function projectForm() {
        return $this->belongsTo(ProjectForm::class, 'project_form_id');
    }

    public function encoder() {
        return $this->belongsTo(User::class, 'encoder_id');
    }

    /** @return string[] */
    public function uniqueColumns() {
        return ['encoder_id', 'encoding_id'];
    }
}
