<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    'secret' => env('JWT_SECRET', 'TODO_set_in_production'),

    'ttl' => 180,

    'refresh_ttl' => 20160,

    'algo' => 'RS256',

    'keys' => [
        'public' => env('JWT_PUBLIC_KEY'),
        'passphrase' => '',
    ],

    'user' => App\User::class,

    'identifier' => 'id',

    'required_claims' => ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'],

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', false),

    'providers' => [

        'user' => Tymon\JWTAuth\Providers\User\EloquentUserAdapter::class,

        'jwt' => Tymon\JWTAuth\Providers\JWT\NamshiAdapter::class,

        'auth' => Tymon\JWTAuth\Providers\Auth\IlluminateAuthAdapter::class,

        'storage' => Tymon\JWTAuth\Providers\Storage\IlluminateCacheAdapter::class,

    ],

];
