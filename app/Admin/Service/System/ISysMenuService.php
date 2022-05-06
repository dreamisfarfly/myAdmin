<?php

namespace App\Admin\Service\System;

use App\Admin\Request\System\SysMenuRequest;

/**
 * 菜单 业务层
 *
 * @author zjj
 */
interface ISysMenuService
{

    /**
     * 根据用户ID查询菜单树信息
     *
     * @param int $userId 用户ID
     */
    function selectMenuTreeByUserId(int $userId);

    /**
     * 根据用户ID查询权限
     *
     * @param int $userId 用户ID
     * @return mixed 权限列表
     */
    function selectMenuPermsByUserId(int $userId);

    /**
     * 根据条件菜单列表
     *
     * @param array $queryParams
     * @param int $userId
     * @return mixed
     */
    function selectMenuList(array $queryParams, int $userId);

    /**
     * 构建前端所需要下拉树结构
     *
     * @param array $menus 菜单列表
     * @return mixed 下拉树结构列表
     */
    function buildMenuTreeSelect(array $menus);

    /**
     * 根据角色ID查询菜单树信息
     *
     * @param int $roleId 角色ID
     * @return mixed 选中菜单列表
     */
    function selectMenuListByRoleId(int $roleId);

}
