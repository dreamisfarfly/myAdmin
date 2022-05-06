<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Utils\MenuUtil;
use App\Admin\Core\Utils\TreeSelectUtil;
use App\Admin\Model\SysMenu;
use App\Admin\Model\SysRole;
use App\Admin\Service\System\ISysMenuService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * 根据条件菜单列表
     *
     * @param array $queryParams
     * @param int $userId
     * @return array|Builder[]|Collection
     */
    function selectMenuList(array $queryParams, int $userId)
    {
        if(!SecurityUtils::isAdmin($userId))
        {
            $queryParams['userId'] = $userId;
        }
        return SysMenu::selectMenuList($queryParams);
    }

    /**
     * 构建前端所需要下拉树结构
     *
     * @param array $menus 菜单列表
     * @return array 下拉树结构列表
     */
    function buildMenuTreeSelect(array $menus): array
    {
        return TreeSelectUtil::collect($menus,0,'menuId', 'menuName');
    }

    /**
     * 根据角色ID查询菜单树信息
     *
     * @param int $roleId 角色ID
     * @return mixed 选中菜单列表
     */
    function selectMenuListByRoleId(int $roleId)
    {
        $role = SysRole::selectRoleById($roleId);
        $menuList = SysMenu::selectMenuListByRoleIdAssociationShow($roleId,$role['menuCheckStrictly']);
        $menuIds = [];
        foreach ($menuList as $item)
        {
            array_push($menuIds,$item['menuId']);
        }
        return $menuIds;
    }
}
