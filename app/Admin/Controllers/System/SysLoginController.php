<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Request\System\LoginBody;
use Illuminate\Http\JsonResponse;

/**
 * 登录验证
 *
 * @author zjj
 */
class SysLoginController extends BaseController
{

    /**
     * 登录方法
     *
     * @param LoginBody $loginBody
     * @return JsonResponse
     */
    public function login(LoginBody $loginBody): JsonResponse
    {
        return AjaxResult::success();
    }

    /**
     * 获取用户信息
     */
    public function getInfo()
    {

    }

    /**
     * 获取路由信息
     */
    public function getRouters()
    {

    }

}
