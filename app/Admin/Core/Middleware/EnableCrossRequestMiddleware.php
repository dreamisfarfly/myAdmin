<?php

namespace App\Admin\Core\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 跨域
 *
 * @author zjj
 */
class EnableCrossRequestMiddleware
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
        $response = $next($request);
        $IlluminateResponse = 'Illuminate\Http\Response';
        $SymfonyResponse = 'Symfony\Component\HttpFoundation\Response';
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN, istoken',
            'Access-Control-Expose-Headers' => 'Authorization, authenticated',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
        ];


        if ($response instanceof $IlluminateResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        if ($response instanceof $SymfonyResponse) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }

        //$response->header('Access-Control-Allow-Origin', '*');
        //$response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN, istoken');
        //$response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
        //$response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS, DELETE');
        //$response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
    }

}
