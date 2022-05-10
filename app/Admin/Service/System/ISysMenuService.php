<?php

namespace App\Admin\Service\System;

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

    /**
     * 根据菜单ID查询信息
     *
     * @param int $menuId 菜单ID
     * @return mixed 菜单信息
     */
    function selectMenuById(int $menuId);

    /**
     * 校验菜单名称是否唯一
     *
     * @param array $sysMenu 菜单信息
     * @param int|null $menuId
     * @return mixed 结果
     */
    function checkMenuNameUnique(array $sysMenu, ?int $menuId = null);

    /**
     * 新增保存菜单信息
     *
     * @param array $sysMenu 菜单信息
     * @return mixed 结果
     */
    function insertMenu(array $sysMenu);

    /**
     * 修改保存菜单信息
     *
     * @param int $menuId 菜单编号
     * @param array $sysMenu 菜单信息
     * @return mixed 结果
     */
    function updateMenu(int $menuId, array $sysMenu);

    /**
     * 是否存在菜单子节点
     *
     * @param int $menuId 菜单ID
     * @return mixed 结果 true 存在 false 不存在
     */
    function hasChildByMenuId(int $menuId);

    /**
     * 查询菜单是否存在角色
     *
     * @param int $menuId 菜单ID
     * @return mixed 结果 true 存在 false 不存在
     */
    function checkMenuExistRole(int $menuId);

    /**
     * 删除菜单管理信息
     *
     * @param int $menuId 菜单ID
     * @return mixed 结果
     */
    function deleteMenuById(int $menuId);

}
