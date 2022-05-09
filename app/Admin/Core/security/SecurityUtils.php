<?php

namespace App\Admin\Core\Security;

/**
 * 安全服务工具类
 *
 * @author zjj
 */
class SecurityUtils
{

    /**
     * 获取用户账户
     * @return mixed
     */
    public static function getUsername()
    {
        return self::getLoginUser()['sysUser']['userName'];
    }

    /**
     * 获取用户
     * @return mixed|null
     */
    public static function getLoginUser()
    {
        return (new TokenService())->getLoginUser();
    }

    /**
     * 是否为管理员
     *
     * @param int|null $userId 用户ID
     * @return bool 结果
     */
    public static function isAdmin(?int $userId): bool
    {
        return 1 == $userId;
    }

}
