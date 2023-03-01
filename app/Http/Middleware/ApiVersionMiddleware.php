<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Services\ApiVersionService;

/**
 * Send the incoming request to the appropriate versioned controller.
 */
class ApiVersionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $version = ApiVersionService::get($request);
        if (! ApiVersionService::isValid($version)) {
            throw new Exception('Invalid version provided.');
        }

        ApiVersionService::setVersionedControllerInRoute($request->route(), $version);

        return $next($request);
    }
}
