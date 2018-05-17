<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class AuthorizeUserAPI {

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $header = $request->header(static::HEADER);
        $matches = $this->decodeHeaderOrFail($header);
        $scheme = $matches[1];
        $key = $matches[2];

        $this->validateSchemeOrFail($scheme);
        $this->validateTokenOrFail($key);

        return $next($request);
    }

    /**
     * @param $header string
     * @throws AuthenticationException
     */
    protected function decodeHeaderOrFail($header) {
        $res = preg_match(static::HEADER_REGEX, $header, $matches);
        if ($res !== 1) {
            throw new AuthenticationException('INVALID_HEADER_FORMAT');
        }
        return $matches;
    }

    /**
     * @param $scheme
     * @throws AuthenticationException
     */
    protected function validateSchemeOrFail($scheme) {
        if ($scheme !== static::AUTH_SCHEME) {
            throw new AuthenticationException('INVALID_AUTH_SCHEME');
        }
    }

    /**
     * @param $token
     * @throws AuthenticationException
     */
    protected function validateTokenOrFail($token) {
        $secret = $this->getApiSecret();
        if ($secret !== $token) {
            throw new AuthenticationException('INVALID_API_KEY');
        }
    }

    protected function getApiSecret() {
        return config('custom.auth_api_secret');
    }


    const HEADER = 'Authorization';
    const AUTH_SCHEME = 'key';
    const HEADER_REGEX = '/^(\w+) (\w+)$/';
}
