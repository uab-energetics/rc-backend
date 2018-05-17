<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller {

    public function login(Request $request, JWTAuth $auth) {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        //verify that the user exists
        $user = $this->findUser($credentials);
        if (!$user) {
            return response()->json(self::INVALID_CREDENTIALS, 401);
        }

        $userInfo = $this->getUserInfo($user);

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = $auth->attempt($credentials)) {
                return response()->json(self::INVALID_CREDENTIALS, 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(self::FAILURE, 500);
        }

        // all good so return the token
        return okMessage("Successfully logged in", 200, [
            'token' => $token,
//            'user' => $userInfo
            'user' => $user
        ]);
    }

    public function register(Request $request, JWTAuth $auth) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->findUser($request->all());

        if ($user !== null) {
            return response()->json([
                'status' => 'USER_ALREADY_EXISTS',
                'msg' => "The user specified already exists"
            ], 403);
        }

        $user = $this->create($request->all());
        $this->onRegistered($user, $request);

        try {
            $token = $auth->fromUser($user);
        } catch (JWTException $e) {
            return response()->json(self::FAILURE, 500);
        }

        return okMessage("Successfully registered", 200, [
            'token' => $token,
//            'user' => $this->getUserInfo($user)
            'user' => $user
        ]);
    }

    protected function findUser(array $credentials) {
        return User::query()->where('email', '=', $credentials['email'])->first();
    }

    /** Create a new user instance after a valid registration.
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data) {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        event(new UserCreated($user));
        return $user;
    }

    protected function onRegistered($user, $request) {
        event(new Registered($user), $request->callback);
    }

    // Why..?
    protected function getUserInfo(User $user) {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ];
    }

    const INVALID_CREDENTIALS = [
        'status' => 'INVALID_CREDENTIALS',
        'msg' => 'No user found with the specified credentials'
    ];

    const INACTIVE = [
        'status' => 'USER_INACTIVE',
        'msg' => 'You have not validated your email'
    ];

    const FAILURE = [
        'status' => 'TOKEN_CREATION_FAILURE',
        'msg' => "This is a bug"
    ];
}
