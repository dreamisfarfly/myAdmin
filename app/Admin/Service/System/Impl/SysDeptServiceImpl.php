<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Utils\TreeSelectUtil;
use App\Admin\Model\SysDept;
use App\Admin\Model\SysUser;
use App\Admin\Service\System\ISysDeptService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * @return bool 结果 true 存在 false 不存在
     */
    function checkDeptExistUser(int $deptId): bool
    {
        return SysUser::checkDeptExistUser($deptId) > 0;
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
     * 查询部门管理数据
     *
     * @param array $queryParams 部门信息
     * @return Builder[]|Collection
     */
    function selectDeptList(array $queryParams = [])
    {
        return SysDept::selectDeptList($queryParams);
    }

    /**
     * 构建前端所需要下拉树结构
     *
     * @param array $deps 部门列表
     * @return array 下拉树结构列表
     */
    function buildDeptTreeSelect(array $deps): array
    {
        return TreeSelectUtil::collect($deps, 0,'deptId','deptName');
    }

    /**
     * 根据部门ID查询信息
     *
     * @param int $depId 部门ID
     * @return Builder|Model|object|null 部门信息
     */
    function selectDeptById(int $depId)
    {
        return SysDept::selectDeptById($depId);
    }

    /**
     * 校验部门名称是否唯一
     *
     * @param array $sysDept 部门信息
     * @param int|null $deptId 部门编号
     * @return string
     * @return string
     */
    function checkDeptUnique(array $sysDept, ?int $deptId = null): string
    {
        $info = SysDept::checkDeptUnique($sysDept);
        if($info != null && $info['deptId'] != $deptId)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 新增保存部门信息
     *
     * @param array $sysDept 部门信息
     * @return bool 结果
     * @throws ParametersException
     */
    function insertDept(array $sysDept): bool
    {
        $info = SysDept::selectDeptById($sysDept['parentId']);
        // 如果父节点不为正常状态,则不允许新增子节点
        if(!UserConstants::DEPT_NORMAL == $info['status'])
        {
            throw new ParametersException('部门停用，不允许新增');
        }
        $sysDept['ancestors'] = $info['ancestors'].','.$sysDept['parentId'];
        return SysDept::insertDept($sysDept);
    }

    /**
     * 根据ID查询所有子部门（正常状态）
     *
     * @param int $deptId 部门ID
     * @return Builder[]|Collection 子部门数
     */
    function selectNormalChildrenDeptById(int $deptId)
    {
        return SysDept::selectNormalChildrenDeptById($deptId);
    }

    /**
     * 修改保存部门信息
     *
     * @param int $deptId 部门编号
     * @param array $sysDept 部门信息
     * @return int 结果
     */
    function updateDept(int $deptId, array $sysDept): int
    {
        $result = 0;
        try {
            DB::beginTransaction();
            $newParentDept = SysDept::selectDeptById($sysDept['parentId']);
            $oldDept = SysDept::selectDeptById($deptId);
            if($newParentDept != null && $oldDept != null)
            {
                $newAncestors = $newParentDept['ancestors'] . ',' . $newParentDept['deptId'];
                $oldAncestors = $oldDept['ancestors'];
                $sysDept['ancestors'] = $newAncestors;
                self::updateDeptChildren($deptId, $newAncestors, $oldAncestors);
            }
            $result = SysDept::updateDept($deptId, $sysDept);
            if(UserConstants::DEPT_NORMAL == $sysDept['status'])
            {
                // 如果该部门是启用状态，则启用该部门的所有上级部门
                self::updateParentDeptStatus($deptId, $sysDept);
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
        };
        return $result;
    }

    /**
     * 修改子元素关系
     *
     * @param int $deptId 被修改的部门ID
     * @param string $newAncestors 新的父ID集合
     * @param string $oldAncestors 旧的父ID集合
     */
    function updateDeptChildren(int $deptId, string $newAncestors, string $oldAncestors)
    {
        $children = SysDept::selectChildrenDeptById($deptId);
        foreach ($children as $item)
        {
            $ancestors = substr_replace($children['ancestors'],$newAncestors,strpos($children['ancestors'],$oldAncestors),strlen($oldAncestors));
            //$ancestors = str_ireplace($oldAncestors, $newAncestors, $children['ancestors']);
            SysDept::updateDept($item['deptId'],['ancestors' => $ancestors]);
        }
    }

    /**
     * 修改该部门的父级部门状态
     *
     * @param int $deptId 部门编号
     * @param array $sysDept 当前部门
     */
    private function updateParentDeptStatus(int $deptId, array $sysDept)
    {
        $updateBy = $sysDept['updateBy'];
        $info = SysDept::selectDeptById($deptId);
        $ancestors = explode(',', $info['ancestors']);
        if(count($ancestors) > 0){
            SysDept::updateDeptStatus($ancestors,[
                'status' => $info['status'],
                'updateBy' => $updateBy
            ]);
        }
    }

}
