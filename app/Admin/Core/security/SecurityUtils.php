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
