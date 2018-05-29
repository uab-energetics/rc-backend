<?php

namespace App\Messaging;

class RabbitMessage {
    private $data;
    private $msg;

    public function __construct($data, $msg) {
        $this->data = $data;
        $this->msg = $msg;
    }

    public function ack() {
        $msg = $this->msg;
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    public function getPayload() {
        return $this->data;
    }
}
