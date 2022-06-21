<?php

namespace App\Admin\Core\Middleware;

use App\Admin\Core\Security\TokenService;
use App\Admin\Core\Utils\HttpUtil;
use App\Admin\Model\SysOperateLog;
use Closure;
use Illuminate\Http\Request;
use ReflectionClass;

/**
 * 系统操作记录中间件
 *
 * @author zjj
 */
class OperationLogMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $classData = explode('@',$request->route()->getActionName());
        try {
            $reflection = new ReflectionClass($classData[0]);
            $method = $reflection->getMethod($classData[1]);
            $doc = $method->getDocComment();
            $responseJson = $response->getContent();
            if(preg_match('/@Log\(title\s=\s"?(.*?)"?,\sbusinessType\s=\s"?(.*?)\.(.*?)"?\)/',$doc, $res))
            {
                $responseArr = json_decode($responseJson, true);
                $status = 0;
                if(!is_array($responseArr) || count($responseArr) == 0){
                    return $response;
                }
                if($responseArr['code'] != 200){
                    $status = 1;
                }
                if(count($res) != 4){
                    return $response;
                }
                $constantObj = new ReflectionClass("App\Admin\Core\Constant\\".$res[2]);
                $loginUser = (new TokenService())->getLoginUser();
                $ip = $request->getClientIp();
                SysOperateLog::insert([
                    'title' => $res[1], //模块标题
                    'business_type' => $constantObj->getConstant($res[3]), //业务类型
                    'method' => $request->route()->getAction('controller'), //方法名称
                    'request_method' => $request->getMethod(), //请求方式,
                    'operator_type' => 0, //操作类别
                    'oper_name' => $loginUser['sysUser']['userName'], //操作人员
                    'dept_name' => $loginUser['sysUser']['deptName'], //部门名称,
                    'oper_url' => $request->route()->getAction('prefix'), //请求URL ccc
                    'oper_ip' => $ip, //主机地址
                    'oper_location' => HttpUtil::getAddress($ip), //操作地点
                    'oper_param' => json_encode($request->all()), //请求参数
                    'json_result' => $response->getContent(), //返回参数,
                    'status' => $status, //操作状态
                    'error_msg' => $responseArr['msg'], //错误消息
                    'oper_time' => date('Y-m-d H:m:s')
                ]);
            }
        } catch (\ReflectionException $e) {}
        return $response;
    }

}
