<?php

namespace App\Admin\Service\System;

/**
 * 用户接口
 *
 * @author zjj
 */
interface ISysUserService
{

    /**
     * 根据条件分页查询用户列表
     *
     * @return mixed
     */
    function selectUserList();

}
