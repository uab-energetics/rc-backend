<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EncodingChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $encoding_id;

    public function __construct($encoding_id) {
        $this->encoding_id = $encoding_id;
    }

}
