<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Model\SysMenu;
use App\Admin\Model\SysRole;
use App\Admin\Service\System\ISysPermissionService;

/**
 * 用户权限处理
 *
 * @author zjj
 */
class SysPermissionServiceImpl implements ISysPermissionService
{

    /**
     * 获取角色数据权限
     *
     * @param array $sysUser 用户信息
     * @return array 角色权限信息
     */
    function getRolePermission(array $sysUser): array
    {
        $roles = [];
        if(SecurityUtils::isAdmin($sysUser['userId']))
        {
            array_push($roles, 'admin');
        }
        else
        {
            $sysRoleList = SysRole::selectRolePermissionByUserId($sysUser['userId'])->toArray();
            foreach ($sysRoleList as $item)
            {
                array_push($roles, $item['roleKey']);
            }
        }
        return $roles;
    }

    /**
     * 获取菜单数据权限
     *
     * @param array $sysUser 用户信息
     * @return array 菜单权限信息
     */
    function getMenuPermission(array $sysUser): array
    {
        $perms = [];
        if(SecurityUtils::isAdmin($sysUser['userId']))
        {
            array_push($perms, '*:*:*');
        }
        else
        {
            $permsArray = SysMenu::selectMenuPermsByUserId($sysUser['userId']);
            foreach ($permsArray as $item)
            {
                if($item->perms != ''){
                    array_push($perms, $item->perms);
                }
            }
        }
        return $perms;
    }
}
