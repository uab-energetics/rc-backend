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

//    'secret' => env('JWT_SECRET', 'TODO_set_in_production'),
//    'public' => 'file://.keys/pub/rc-auth.pem',
//    'public' => file_get_contents('/.keys/pub/rc-auth.pub'),
//    'public' => env('JWT_PUBLIC_KEY'),



    // This library is GARBAGE!!!

    'keys' => [
        'public' => 'file:///.keys/pub/rc-auth.pem'
    ],

    'ttl' => 180,

    'refresh_ttl' => 20160,

    'algo' => 'RS256',

    'user' => App\User::class,

    'identifier' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | Specify the required claims that must exist in any token.
    | A TokenInvalidException will be thrown if any of these claims are not
    | present in the payload.
    |
    */

    'required_claims' => [],

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    'providers' => [

        /*
        |--------------------------------------------------------------------------
        | User Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to find the user based
        | on the subject claim
        |
        */

        'user' => Tymon\JWTAuth\Providers\User\EloquentUserAdapter::class,

        'jwt' => Tymon\JWTAuth\Providers\JWT\NamshiAdapter::class,

        'auth' => Tymon\JWTAuth\Providers\Auth\IlluminateAuthAdapter::class,

        'storage' => Tymon\JWTAuth\Providers\Storage\IlluminateCacheAdapter::class,

    ],

];
