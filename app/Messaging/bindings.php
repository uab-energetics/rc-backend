<?php


return [
    [
        'exchange' => 'users.created',
        'queue' => 'process-new-user',
        'event' => \App\Events\UserCreated::class
    ]
];