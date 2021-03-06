<?php


/**
 * This file was created to respond to user created events from the custom auth service.
 * However, we have since moved to Firebase authentication, so this file should be removed at some point.
 * We need to improve test coverage to safely delete it..
 */

namespace App\Console\Commands;

use App\Services\RabbitMQ\RabbitMQService;
use Illuminate\Console\Command;
use PhpAmqpLib\Exception\AMQPConnectionException;

class RabbitMQListen extends Command {
    protected $signature = 'rabbitmq:listen';

    protected $description = 'Starts a queue worker for RabbitMQ';

    public function handle() {
        $service = null;
        $retries = config('rabbitmq.connection.retries');
        $sleepTime = config('rabbitmq.connection.wait_time');

        print("Trying to connect to RabbitMQ..." . PHP_EOL);
        while ($service === null && $retries > 0) {
            try {
                $service = app()->make(RabbitMQService::class);
            } catch (\ErrorException $e) {
                print("Could not connect to RabbitMQ. $retries tries remaining..." . PHP_EOL);
                $retries--;
                sleep($sleepTime);
            } catch (\Exception $e) {
                print("Miscellaneous error. Quitting..." . PHP_EOL);
                print ($e->getFile().':'.$e->getLine().':'.get_class($e).' - '.$e->getMessage().PHP_EOL);
                exit(1);
            }
        }

        if ($retries <= 0) {
            print("Failed to connect to RabbitMQ. Shutting Down." . PHP_EOL);
            exit(1);
        }


        print("Listening for RabbitMQ Messages" . PHP_EOL);
        $service->listen();

        print("Done listening. Closing connection" . PHP_EOL);
    }
}
