<?php

namespace App\Admin\Service\System;

/**
 * 角色服务接口
 */
interface ISysRoleService
{

    /**
     * 根据条件分页查询角色数据
     *
     * @param array $queryParam 查询参数
     * @return mixed
     */
    function selectRoleList(array $queryParam);

    /**
     * 通过角色ID查询角色
     *
     * @param int $roleId 角色ID
     * @return mixed 角色对象信息
     */
    function selectRoleById(int $roleId);

    /**
     * 校验角色是否允许操作
     *
     * @param int $roleId 角色信息
     * @return mixed
     */
    function checkRoleAllowed(int $roleId);

    /**
     * 批量删除角色信息
     *
     * @param array $roleIds 需要删除的角色ID
     * @return mixed 结果
     */
    function deleteRoleByIds(array $roleIds);

    /**
     * 通过角色ID查询角色使用数量
     *
     * @param int $roleId 角色ID
     * @return mixed 结果
     */
    function countUserRoleByRoleId(int $roleId);

    /**
     * 查询所有角色
     *
     * @return mixed 角色列表
     */
    function selectRoleAll();

    /**
     * 根据用户ID获取角色选择框列表
     *
     * @param int $userId 用户ID
     * @return mixed 选中角色ID列表
     */
    function selectRoleListByUserId(int $userId);

}
