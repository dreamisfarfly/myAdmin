<?php

namespace App\Admin\Service\System;

/**
 * 部门管理 服务层
 *
 * @author zjj
 */
interface ISysDeptService
{

    /**
     * 是否存在部门子节点
     *
     * @param int $deptId
     * @return mixed
     */
    function hasChildByDeptId(int $deptId);

    /**
     * 查询部门是否存在用户
     *
     * @param int $deptId 部门ID
     * @return mixed 结果 true 存在 false 不存在
     */
    function checkDeptExistUser(int $deptId);

    /**
     * 删除部门管理信息
     *
     * @param int $deptId 部门ID
     * @return mixed 结果
     */
    function deleteDeptById(int $deptId);

    /**
     * 查询部门管理数据
     *
     * @param array $queryParams 部门信息
     * @return mixed 部门信息集合
     */
    function selectDeptList(array $queryParams = []);

    /**
     * 构建前端所需要下拉树结构
     *
     * @param array $deps 部门列表
     * @return mixed 下拉树结构列表
     */
    function buildDeptTreeSelect(array $deps);

    /**
     * 根据部门ID查询信息
     *
     * @param int $depId 部门ID
     * @return mixed 部门信息
     */
    function selectDeptById(int $depId);

    /**
     * 校验部门名称是否唯一
     *
     * @param array $sysDept 部门信息
     * @param int|null $deptId 部门编号
     * @return mixed
     */
    function checkDeptUnique(array $sysDept, ?int $deptId = null);

    /**
     * 新增保存部门信息
     *
     * @param array $sysDept 部门信息
     * @return mixed 结果
     */
    function insertDept(array $sysDept);

    /**
     * 根据ID查询所有子部门（正常状态）
     *
     * @param int $deptId 部门ID
     * @return mixed 子部门数
     */
    function selectNormalChildrenDeptById(int $deptId);

    /**
     * 修改保存部门信息
     *
     * @param int $deptId 部门编号
     * @param array $sysDept 部门信息
     * @return mixed 结果
     */
    function updateDept(int $deptId, array $sysDept);

}
