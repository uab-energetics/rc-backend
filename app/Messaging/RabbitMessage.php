<?php

namespace App\Messaging;

class RabbitMessage {
    private $data;
    private $ack;

    public function __construct($data, \Closure $ack) {
        $this->data = $data;
        $this->ack = $ack;
    }
}
