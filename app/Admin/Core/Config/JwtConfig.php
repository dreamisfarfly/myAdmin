<?php

namespace App\Admin\Core\Config;

/**
 * Jwt 配置
 */
interface JwtConfig
{

    /**
     * 令牌自定义标识
     */
    const HEADER = 'Authorization';

    /**
     * 令牌秘钥
     */
    const SECRET = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * 令牌有效期（默认30分钟）
     */
    const EXPIRE_TIME = 30;

}
