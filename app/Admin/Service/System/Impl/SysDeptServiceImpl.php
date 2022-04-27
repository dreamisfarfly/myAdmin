<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysDept;
use App\Admin\Service\System\ISysDeptService;

/**
 * 部门管理 服务实现
 *
 * @author ruoyi
 */
class SysDeptServiceImpl implements ISysDeptService
{

    /**
     * 是否存在部门子节点
     *
     * @param int $deptId
     * @return bool
     */
    function hasChildByDeptId(int $deptId): bool
    {
        return SysDept::hasChildByDeptId($deptId) > 0;
    }

    /**
     * 查询部门是否存在用户
     *
     * @param int $deptId 部门ID
     * @return mixed 结果 true 存在 false 不存在
     */
    function checkDeptExistUser(int $deptId)
    {
        return SysDept::checkDeptExistUser($deptId) > 0;
    }

    /**
     * 删除部门管理信息
     *
     * @param int $deptId 部门ID
     * @return int 结果
     */
    function deleteDeptById(int $deptId): int
    {
        return SysDept::deleteDeptById($deptId);
    }

    /**
     * 校验部门名称是否唯一
     *
     * @param array $dept 部门信息
     * @return mixed 结果
     */
    function checkDeptNameUnique(array $dept)
    {
        // TODO: Implement checkDeptNameUnique() method.
    }
}
