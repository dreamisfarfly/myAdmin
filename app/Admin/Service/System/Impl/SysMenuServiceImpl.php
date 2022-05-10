<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Utils\MenuUtil;
use App\Admin\Core\Utils\TreeSelectUtil;
use App\Admin\Model\SysMenu;
use App\Admin\Model\SysRole;
use App\Admin\Service\System\ISysMenuService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return void 权限列表
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
     * @return array 选中菜单列表
     */
    function selectMenuListByRoleId(int $roleId): array
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

    /**
     * 根据菜单ID查询信息
     *
     * @param int $menuId 菜单ID
     * @return Builder|Model|object|null 菜单信息
     */
    function selectMenuById(int $menuId)
    {
        $menuInfo = SysMenu::selectMenuById($menuId);
        if($menuInfo != null)
        {
            $menuInfo['isFrame'] = ''.$menuInfo['isFrame'];
        }
        return $menuInfo;
    }

    /**
     * 校验菜单名称是否唯一
     *
     * @param array $sysMenu 菜单信息
     * @param int|null $menuId
     * @return string 结果
     */
    function checkMenuNameUnique(array $sysMenu, ?int $menuId = null): string
    {
        $info = SysMenu::checkMenuNameUnique($sysMenu['menuName'],$sysMenu['parentId']);
        if($info != null && $info['menuId'] != $menuId)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 新增保存菜单信息
     *
     * @param array $sysMenu 菜单信息
     * @return mixed 结果
     */
    function insertMenu(array $sysMenu)
    {
        return SysMenu::insertMenu($sysMenu);
    }
}
