<?php

namespace App\Admin\Core\Security;

use App\Admin\Core\Config\JwtConfig;
use App\Admin\Core\Constant\Constants;
use App\Admin\Core\Utils\HttpUtil;
use App\Admin\Core\Utils\Uuid\IdUtils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\DataSet;

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

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->header = JwtConfig::HEADER;
        $this->secret = JwtConfig::SECRET;
        $this->expireTime = JwtConfig::EXPIRE_TIME;
    }

    /**
     * 获取用户身份信息
     *
     * @return mixed|null 用户信息
     */
    public function getLoginUser()
    {
        // 获取请求携带的令牌
        $token = self::getToken();
        if($token != null)
        {
            $claims = self::parseToken($token);
            // 解析对应的权限以及用户信息
            $tokenArr = json_decode($claims, true);
            if(!isset($tokenArr[Constants::LOGIN_USER_KEY]))
            {
                return null;
            }
            $uuid = $tokenArr[Constants::LOGIN_USER_KEY];
            $userKey = self::getTokenKey($uuid);
            $loginUser = Redis::get($userKey);
            return json_decode($loginUser, true);
        }
        return null;
    }

    /**
     * 创建令牌
     *
     * @param array $loginUser 用户信息
     * @return string 令牌
     */
    public function createToken(array $loginUser): string
    {
        $token = IdUtils::fastUUID();
        $loginUser['token'] = $token;
        self::setUserAgent($loginUser);
        self::refreshToken($loginUser);
        $claims[Constants::LOGIN_USER_KEY] = $token;
        return self::generateJwt($claims);

    }

    /**
     * 验证令牌有效期，相差不足20分钟，自动刷新缓存
     *
     * @param array $loginUser
     */
    public function verifyToken(array $loginUser)
    {
        $expireTime = $loginUser['expireTime'];
        if($expireTime - time() <= self::MILLIS_MINUTE_TEN)
        {
            self::refreshToken($loginUser);
        }
    }

    /**
     * 设置用户身份信息
     * @param array $loginUser
     */
    public function setLoginUser(array $loginUser)
    {
        if($loginUser != null && $loginUser['token'] != null)
        {
            self::refreshToken($loginUser);
        }
    }

    /**
     * 删除登录信息
     *
     * @param array $loginUser
     */
    public function logout(array $loginUser)
    {
        $userKey = self::getTokenKey($loginUser['token']);
        Redis::del($userKey);
    }

    /**
     * 刷新令牌有效期
     *
     * @param array 登录信息
     */
    private function refreshToken(array $loginUser)
    {
        $loginUser['loginTime'] = time(); //登录时间
        $loginUser['expireTime'] = $loginUser['loginTime'] + $this->expireTime * self::MILLIS_MINUTE;
        $userKey = self::getTokenKey($loginUser['token']);
        Redis::setex($userKey, $this->expireTime * 60, json_encode($loginUser));
    }

    /**
     * 设置用户代理信息
     *
     * @param array $loginUser 登录信息
     */
    private function setUserAgent(array &$loginUser)
    {
        $loginUser['ipaddr'] = request()->getClientIp();
        $loginUser['loginLocation'] = HttpUtil::getAddress($loginUser['ipaddr']);
        $loginUser['browser'] = HttpUtil::getBroswer();
        $loginUser['os'] = HttpUtil::getOs();
    }

    /**
     * 从数据声明生成令牌
     *
     * @param array $loginUser 数据声明
     * @return string 令牌
     */
    private function generateJwt(array $loginUser): string
    {
        $token = (new Builder())
            ->withClaim(Constants::LOGIN_USER_KEY, json_encode($loginUser))
            ->getToken(new Sha512(), new Key($this->secret));
        return (string)$token;
    }

    /**
     * 从令牌中获取数据声明
     *
     * @param string $token
     * @return DataSet|mixed|null
     */
    private function parseToken(string $token)
    {
        $configuration =  Configuration::forSymmetricSigner(
            new Sha512(),
            Key\InMemory::base64Encoded($this->secret)
        );
        $data = $configuration->parser()->parse($token)->claims();
        if(null != $data->get(Constants::LOGIN_USER_KEY))
        {
            return $data->get(Constants::LOGIN_USER_KEY);
        }
        return $data;
    }

    /**
     * 获取要缓存token的key
     * @param string $uuid
     * @return string
     */
    private function getTokenKey(string $uuid): string
    {
        return Constants::LOGIN_TOKEN_KEY . $uuid;
    }

    /**
     * 获取请求头中的token
     * @return array|string|string[]|null
     */
    public function getToken()
    {
        $token = request()->header($this->header);
        if(null != $token && self::startsWith(Constants::TOKEN_PREFIX, $token) != 0)
        {
            $token = str_replace(Constants::TOKEN_PREFIX, '', $token);
        }
        return $token;
    }

    /**
     * 从…开始
     * @param $needle
     * @param $haystack
     * @return false|int
     */
    private function startsWith($needle, $haystack)
    {
        return preg_match('/^' . preg_quote($needle, '/') . '/', $haystack);
    }

}
