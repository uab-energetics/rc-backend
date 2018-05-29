<?php

namespace App\Messaging;

class RabbitMessage {
    public $data;
    public $ack;

    public function __construct($data, \Closure $ack) {
        $this->data = $data;
        $this->ack = $ack;
    }

    public function ack() {
        $this->ack();
    }
}
