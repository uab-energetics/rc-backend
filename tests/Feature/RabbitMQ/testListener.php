<?php

use Tests\Feature\RabbitMQ\RabbitMQTest;

config(RabbitMQTest::CONFIG);
Artisan::call('rabbitmq:listen');