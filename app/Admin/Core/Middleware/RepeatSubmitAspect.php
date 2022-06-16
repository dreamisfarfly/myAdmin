<?php

namespace App\Admin\Core\Middleware;

use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\TokenService;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Redis;
use ReflectionClass;

/**
 * 重复提交aop
 *
 * @author zjj
 */
class RepeatSubmitAspect
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ParametersException
     */
    public function handle(Request $request, Closure $next)
    {
        $tokenService = new TokenService();
        $classData = explode('@',$request->route()->getActionName());
        try {
            $reflection = new ReflectionClass($classData[0]);
            $method = $reflection->getMethod($classData[1]);
            $doc = $method->getDocComment();
            if(preg_match('/@Log\(title\s=\s"?(.*?)"?,\sbusinessType\s=\s"?(.*?)\.(.*?)"?\)/',$doc))
            {
                $token = $tokenService->getToken();
                if(Redis::exists($token.$request->route()->getPrefix()))
                {
                    throw new ParametersException('重复请求，请稍后再试');
                }
                Redis::setex($token,10,'true');
            }
        } catch (\ReflectionException $e) {}
        return $next($request);
    }

}
