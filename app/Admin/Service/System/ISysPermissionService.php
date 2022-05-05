<?php

namespace App\Admin\Service\System;

/**
 * 用户权限处理
 *
 * @author zjj
 */
interface ISysPermissionService
{

    /**
     * 获取角色数据权限
     *
     * @param array $sysUser 用户信息
     * @return mixed 角色权限信息
     */
    function getRolePermission(array $sysUser);

    /**
     * 获取菜单数据权限
     *
     * @param array $sysUser 用户信息
     * @return mixed 菜单权限信息
     */
    function getMenuPermission(array $sysUser);

}
