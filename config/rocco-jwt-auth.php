<?php

return [
    'public_key' => env('JWT_PUBLIC_KEY'),
    'algorithm' => env('JWT_ALGORITHM', 'RS256'),
    'user_id_field' => env(null, 'id'), // uses subject of JWT
    'jwt_user_id' => 'uuid'
];
