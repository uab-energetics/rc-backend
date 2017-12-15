<?php

namespace App\Http\Controllers;

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
            return response()->json(self::INVALID, 401);
        }

        $userInfo = $this->getUserInfo($user);

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(self::INVALID, 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(self::FAILURE, 500);
        }

        // all good so return the token
        return response()->json([
            'status' => 'ok',
            'msg' => "Successfully logged in",
            'token' => $token,
            'user' => $userInfo
        ], 200);
    }

    public function register(Request $request, JWTAuth $auth) {
        $this->validator($request->all())->validate();

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

        return response()->json([
            'status' => 'ok',
            'msg' => "Successfully registered",
            'token' => $token,
            'user' => $this->getUserInfo($user)
        ], 200);
    }

    /** Get a validator for an incoming registration request.
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function onRegistered($user, $request) {
        event(new Registered($user), $request->callback);
    }

    protected function getUserInfo(User $user) {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ];
    }

    const INVALID = [
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
