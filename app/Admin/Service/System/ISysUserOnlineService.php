<?php

namespace App\Admin\Service\System;

/**
 * 在线用户 服务层
 *
 * @author zjj
 */
interface ISysUserOnlineService
{

    /**
     * 设置在线用户信息
     *
     * @param array $loginUser 用户信息
     * @return mixed 在线用户
     */
    function loginUserToUserOnline(array $loginUser);

}
