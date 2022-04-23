<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Service\System\ISysLoginService;

/**
 * 登录接口实现
 *
 * @author zjj
 */
class SysLoginServiceImpl implements ISysLoginService
{

    /**
     * 登录验证
     *
     * @param string $username  用户名
     * @param string $password  密码
     * @param string $code  验证码
     * @param string $uuid  唯一标识
     * @return mixed 结果
     */
    public function login(string $username, string $password, string $code, string $uuid)
    {
        return 'test';
    }
}
