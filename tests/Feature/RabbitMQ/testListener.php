<?php

use Tests\Feature\RabbitMQ\PublishTest;


config(PublishTest::CONFIG);

Artisan::call('rabbitmq:listen');