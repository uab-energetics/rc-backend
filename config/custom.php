<?php

return [

    'default_user_image' => 'https://image.flaticon.com/icons/svg/149/149071.svg',
    'pagination_max_size' => env('PAGINATION_MAX_SIZE', 500),

    'auth_api_secret' => env('AUTH_API_SECRET'),


    'jwt_auth' => [
        'public_key' => env('JWT_PUBLIC_KEY'),
        'algorithm' => env('JWT_ALGORITHM', 'RS256'),
        'user_id_field' => env(null, 'id'), // uses subject of JWT
        'jwt_user_id' => 'uuid'
    ]

];
