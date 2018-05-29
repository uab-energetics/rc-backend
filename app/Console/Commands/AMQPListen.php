<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AMQPListen extends Command
{
    protected $signature = 'rabbitmq:listen';

    protected $description = 'Starts a queue worker for RabbitMQ';

    public function handle() {
        require __DIR__ . '/../../Messaging/listen.php';
    }
}
