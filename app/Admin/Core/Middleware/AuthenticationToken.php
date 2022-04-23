<?php

namespace App\Admin\Core\Middleware;

use Illuminate\Http\Request;
use Closure;

/**
 * 中间件验证token
 */
class AuthenticationToken
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

}
