<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoccoJWTAuth
{
    /**
     *
     * STEP-BY-STEP:
     * ---------------------
     * 1. Grab the token out of the request
     * 2. Load the public key and JWT algorithm
     * 3. Decode the JWT
     * 4. Lookup the user in our database
     * 5. Set the currently logged in user
     */
    public function handle(Request $request, Closure $next)
    {
        if(config('rocco-jwt-auth.no-jwt-middleware', false)) {
            $next();
        }

        $public_key = config('rocco-jwt-auth.public_key');
        $algorithm_type = config('rocco-jwt-auth.algorithm');
        $user_id_field = config('rocco-jwt-auth.user_id_field');   // the property on the user model
        $jwt_user_id = config('rocco-jwt-auth.jwt_user_id');       // the property on the JWT corresponding to the user model

        if(!($public_key && $algorithm_type && $user_id_field && $jwt_user_id))
            throw new \Exception("Missing required configuration for JWT middleware");

        $token = $request->bearerToken();
        if(!$token)
            return response()->json([ 'msg' => 'token not set in Authorization: Bearer ... token' ], 401);

        try {
            $decoded = JWT::decode($token, $public_key, [$algorithm_type]);
        } catch (ExpiredException $expired) {
            return response()->json([ 'msg' => 'token expired' ], 401);
        }

        $exists_users = User::where($user_id_field, ((array)$decoded)[$jwt_user_id])->get();
        if(count($exists_users) === 0)
            return response()->json([
                'msg' => 'Token is valid, but user is not in database',
                'details' => [
                    'token' => (array)$decoded,
                    'user_id_field' => $user_id_field,
                    'jwt_user_id' => $jwt_user_id
                ]
            ], 500);

        $user = $exists_users[0];
        Auth::setUser($user);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}