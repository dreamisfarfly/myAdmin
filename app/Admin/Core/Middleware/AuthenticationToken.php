<?php

namespace App\Admin\Core\Middleware;

use App\Admin\Core\Exception\AuthorityCertificationException;
use App\Admin\Core\Security\Authentication;
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
     * @throws AuthorityCertificationException
     */
    public function handle(Request $request, Closure $next)
    {
        Authentication::detectionToken();
        return $next($request);
    }

}
