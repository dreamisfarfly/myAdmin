<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Utils\MenuUtil;
use App\Admin\Model\SysMenu;
use App\Admin\Service\System\ISysMenuService;

/**
 * 菜单 业务层
 *
 * @author zjj
 */
class SysMenuServiceImpl implements ISysMenuService
{

    /**
     * 根据用户ID查询菜单树信息
     *
     * @param int $userId 用户ID
     */
    function selectMenuTreeByUserId(int $userId): array
    {
        if(SecurityUtils::isAdmin($userId))
        {
            $menus = SysMenu::selectMenuTreeAll()->toArray();
        }
        else
        {
            $menus = SysMenu::selectMenuTreeByUserId($userId)->toArray();
        }
        return MenuUtil::menuTree($menus);
    }

    /**
     * 根据用户ID查询权限
     *
     * @param int $userId 用户ID
     * @return mixed 权限列表
     */
    function selectMenuPermsByUserId(int $userId)
    {
        $perms = SysMenu::selectMenuPermsByUserId($userId);
    }
}
