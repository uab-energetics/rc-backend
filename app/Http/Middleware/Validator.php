<?php

namespace App\Http\Middleware;

use Closure;

class Validator {
    public function handle($request, Closure $next, $rule_path) {
        $tmp = explode('.', $rule_path);
        $file_name = $tmp[0];
        $rule_name = $tmp[1];
        $rule = ( require app_path('Http/ValidationRules/'.$file_name.'.php') )[$rule_name];

        $request->validate($rule);
        return $next($request);
    }
}
