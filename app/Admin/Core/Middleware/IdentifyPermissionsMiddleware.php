<?php

namespace App\Admin\Core\Middleware;

use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Security\TokenService;
use App\Admin\Model\SysUserRole;
use Illuminate\Http\Request;
use Closure;
use ReflectionClass;

/**
 * 权限鉴别中间件
 *
 * @author zjj
 */
class IdentifyPermissionsMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ParametersException
     */
    public function handle(Request $request, Closure $next)
    {
        $classData = explode('@',$request->route()->getActionName());
        try {
            $reflection = new ReflectionClass($classData[0]);
            $method = $reflection->getMethod($classData[1]);
            $doc = $method->getDocComment();
            if(preg_match('/@PreAuthorize\(hasPermi\s=\s"([\w\W]*)"\)/',$doc, $res))
            {
                if(count($res) != 2){
                    return $next($request);
                }
                $loginUser = (new TokenService())->getLoginUser();
                if(SecurityUtils::isAdmin($loginUser['sysUser']['userId'])){
                    return $next($request);
                }
                if(!SysUserRole::authenticationPermissionString($loginUser['sysUser']['userId'], $res[1])){
                    throw new ParametersException('您没有权限');
                }
            }
        } catch (\ReflectionException $e) {
        }
        return $next($request);
    }

}
