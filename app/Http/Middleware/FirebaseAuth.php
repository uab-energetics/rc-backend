<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Auth\UserRecord;

class FirebaseAuth
{
    public $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase->firebase;
    }

    public function handle(Request $request, Closure $next)
    {
        /* 
        * Decode token and fetch user data from Firebase. 
        * ==================================================
        */
        try {
            $idTokenString = $request->bearerToken();
            $verifiedIdToken = $this->firebase->getAuth()->verifyToken($idTokenString);
        } catch (InvalidToken $e) {
            return $request->status(401)->json([
                'msg' => $e->getMessage()
            ]);
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
