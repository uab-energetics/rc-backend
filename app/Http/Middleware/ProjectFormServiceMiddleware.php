<?php

namespace App\Http\Middleware;

use App\Services\ProjectForms\ProjectFormService;
use Closure;

class ProjectFormServiceMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->route()->hasParameter('project')) {
            $project = $request->route('project');
            app()->singleton(ProjectFormService::class, function ($app) use ($project) {
                return new ProjectFormService($project);
            });
        }
        return $next($request);
    }
}
