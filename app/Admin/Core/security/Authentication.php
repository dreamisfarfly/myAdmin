<?php

namespace App\Admin\Core\Security;

use App\Admin\Core\Exception\AuthorityCertificationException;

/**
 * 权限认知
 *
 * @author zjj
 */
class Authentication
{

    /**
     * 排除URL访问权限
     */
    const Exclude_URL = [
        'admin/logout',
        'admin/login',
        'admin/captchaImage',
    ];

    public static function hasPermit($permissionIdentify)
    {

    }

    /**
     * 验证权限
     *
     * @throws AuthorityCertificationException
     */
    public static function detectionToken()
    {
        if(self::checkoutJurisdictionUrl()){
            return;
        }
        $tokenService = new TokenService();
        $loginUserInfo = $tokenService->getLoginUser();
        if($loginUserInfo == null)
        {
            throw new AuthorityCertificationException();
        }
        $tokenService->verifyToken($loginUserInfo);
    }

    /**
     * 匹配非权限字符串
     * @return bool
     */
    public static function checkoutJurisdictionUrl(): bool
    {
        if(array_search(request()->path(), self::Exclude_URL) !== false)
        {
            return true;
        }
        return false;
    }

}
