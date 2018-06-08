<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalPublicationMap extends UniqueJunction {
    protected $table = "external_publication_map";

    protected $fillable = ['external_id', 'publication_id'];

    public $timestamps = false;

    /** @return string[] */
    public function uniqueColumns() {
        return ['external_id', 'publication_id'];
    }

    public function publication() {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}
