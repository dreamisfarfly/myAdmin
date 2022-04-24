<?php

namespace App\Admin\Core\Security;

use App\Admin\Core\Utils\Uuid\IdUtils;

/**
 * token验证处理
 *
 * @author zjj
 */
class TokenService
{

    /**
     * 令牌自定义标识
     * @var string
     */
    private string $header;

    /**
     * 令牌秘钥
     * @var string
     */
    private string $secret;

    /**
     * 令牌有效期（默认30分钟）
     * @var int
     */
    private int $expireTime;

    /**
     * 毫秒
     */
    private const MILLIS_SECOND = 1000;

    /**
     * 分钟(毫秒数)
     */
    private const MILLIS_MINUTE = 60 * self::MILLIS_SECOND;

    /**
     * 十分钟(毫秒数)
     */
    private const MILLIS_MINUTE_TEN = 20 * 60 * 1000;


    public function __construct()
    {

    }

    public function createToken()
    {
        $token = IdUtils::fastUUID();

    }

    public function refreshToken()
    {

    }

}
