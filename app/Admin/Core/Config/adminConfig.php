<?php
/**
 * 后台管理系统配置
 *
 * @author zjj
 */

return [

    /**
     * 不需要登录的接口地址
     */
    'notLoginAPiSite' => [
        '/login'
    ],

    /**
     * JWt 配置
     */
    'jwt' => [
        'header' => 'Authorization', //令牌自定义标识
        'secret' => 'abcdefghijklmnopqrstuvwxyz', //令牌秘钥
        'expireTime' => 30 //令牌有效期（默认30分钟）
    ]

];
