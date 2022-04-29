<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Request\System\LoginBody;
use App\Admin\Service\System\Impl\SysLoginServiceImpl;
use App\Admin\Service\System\ISysLoginService;
use Illuminate\Http\JsonResponse;

/**
 * 登录验证
 *
 * @author zjj
 */
class SysLoginController extends BaseController
{

    /**
     * @var ISysLoginService|SysLoginServiceImpl
     */
    private ISysLoginService $sysLoginService;

    /**
     * @param SysLoginServiceImpl $sysLoginService
     */
    public function __construct(SysLoginServiceImpl $sysLoginService)
    {
        $this->sysLoginService = $sysLoginService;
    }

    /**
     * 登录方法
     *
     * @param LoginBody $loginBody
     * @return JsonResponse
     * @throws ParametersException
     */
    public function login(LoginBody $loginBody): JsonResponse
    {
        $token = $this->sysLoginService->login(
            $loginBody->get('username'),
            $loginBody->get('password'),
            $loginBody->get('code'),
            $loginBody->get('uuid')
        );
        return (new AjaxResult())->put(['token'=>$token])->success();
    }

    /**
     * 获取用户信息
     */
    public function getInfo()
    {
        return (new AjaxResult())->success();
    }

    /**
     * 获取路由信息
     */
    public function getRouters()
    {

    }

}
