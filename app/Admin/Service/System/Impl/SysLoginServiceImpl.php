<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\TokenService;
use App\Admin\Model\SysUser;
use App\Admin\Service\System\ISysLoginService;
use Illuminate\Support\Facades\Redis;

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
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $code 验证码
     * @param string $uuid 唯一标识
     * @return string 结果
     * @throws ParametersException
     */
    public function login(string $username, string $password, string $code, string $uuid): string
    {
        if(!Redis::exists($uuid) || Redis::get($uuid) != $code)
        {
            throw new ParametersException('验证码错误');
        }
        Redis::set($uuid,null); //删除验证码
        $userInfo = SysUser::selectUserByUsername($username);
        if(null == $userInfo || $userInfo->password != md5($password))
        {
            throw new ParametersException('用户不存在/密码错误');
        }
        return (new TokenService())->createToken(['sysUser' => $userInfo->toArray()]);
    }
}
