<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysUser;
use App\Admin\Service\System\ISysUserService;

/**
 * 用户
 *
 * @author zjj
 */
class SysUserServiceImpl implements ISysUserService
{

    /**
     * 根据条件分页查询用户列表
     *
     * @return mixed
     */
    function selectUserList()
    {
        return SysUser::selectUserList();
    }

}
