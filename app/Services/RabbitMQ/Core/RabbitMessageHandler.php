<?php

namespace App\Services\RabbitMQ\Core;


interface RabbitMessageHandler {

    public function handle(RabbitMessage $message);

}