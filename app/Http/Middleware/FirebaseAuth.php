<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Auth\UserRecord;
use App\Services\FirebaseService;
use Throwable;
use App\Services\Users\UserService;

class FirebaseAuth
{
    public $firebase;
    public $userService;

    public function __construct(FirebaseService $firebase, UserService $userService)
    {
        $this->firebase = $firebase->firebase;
        $this->userService = $userService;
    }

    public function handle(Request $request, Closure $next)
    {
        /* 
        * Decode token and fetch user data from Firebase. 
        * ==================================================
        */
        try {
            $idTokenString = $request->bearerToken();
            if ($idTokenString === null) {
                return response()->json(['msg' => 'No auth token provided'], 401);
            }
            $verifiedIdToken = $this->firebase->getAuth()->verifyIdToken($idTokenString);
        } catch (Throwable $e) {
            return response()->json([
                'msg' => 'Invalid auth token.',
                'error' => $e->getMessage()
            ], 401);
        }
        $uid = $verifiedIdToken->getClaim('sub');
        /** @var UserRecord */
        $firebaseUser = $this->firebase->getAuth()->getUser($uid);

        /* 
        * Find or create the user in our database.
        * ==================================================
        */
        $user = $this->userService->retrieveByEmail($firebaseUser->email);
        if ($user === null) {
            $user = $this->userService->make([
                'uuid' => $firebaseUser->uid,
                'name' => $firebaseUser->displayName,
                'email' => $firebaseUser->email,
                'image' => $firebaseUser->photoUrl
            ]);
        }

        /*
        * Set the current user on the Laravel request.
        * ==================================================
        */
        Auth::setUser($user);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        return $next($request);
    }
}
