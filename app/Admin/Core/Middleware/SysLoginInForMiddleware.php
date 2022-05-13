<?php

namespace App\Admin\Core\Middleware;

use App\Admin\Core\Utils\HttpUtil;
use App\Admin\Model\SysLoginInFor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 系统访问记录中间件
 *
 * @author zjj
 */
class SysLoginInForMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $userName = $request->attributes->get('userName');
        if($userName == null){
            return $response;
        }
        $returnMsg = json_decode($response->getContent(), true);
        $sysLoginInFor = [];
        if(count($returnMsg) > 0)
        {
            $sysLoginInFor['msg'] = $returnMsg['msg']; //提示消息
            $returnMsg['code'] == 200? $sysLoginInFor['status'] = 0: $sysLoginInFor['status'] = 1;//登录状态
        }
        $sysLoginInFor['userName'] = $userName;
        $sysLoginInFor['ipaddr'] = $request->getClientIp(); //登录IP地址
        $sysLoginInFor['browser'] = HttpUtil::getBroswer();
        $sysLoginInFor['os'] = HttpUtil::getOs();
        $sysLoginInFor['loginLocation'] = HttpUtil::getAddress($sysLoginInFor['ipaddr']);
        SysLoginInFor::insertSysLoginInFor($sysLoginInFor);
        return $response;
    }

}
