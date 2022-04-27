<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Exception\ParametersException;
use App\Admin\Model\SysRole;
use App\Admin\Model\SysRoleDept;
use App\Admin\Model\SysRoleMenu;
use App\Admin\Model\SysUserRole;
use App\Admin\Service\System\ISysRoleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * 角色服务接口实现
 */
class SysRoleServiceImpl implements ISysRoleService
{

    /**
     * 根据条件分页查询角色数据
     *
     * @return LengthAwarePaginator
     */
    public function selectRoleList(): LengthAwarePaginator
    {
        return SysRole::selectRoleList();
    }

    /**
     * 通过角色ID查询角色
     *
     * @param int $roleId 角色ID
     * @return mixed 角色对象信息
     */
    function selectRoleById(int $roleId)
    {
        // TODO: Implement selectRoleById() method.
    }


    /**
     * 批量删除角色信息
     *
     * @param array $roleIds 需要删除的角色ID
     * @return bool 结果
     * @throws ParametersException
     */
    function deleteRoleByIds(array $roleIds): bool
    {
        foreach ($roleIds as $id)
        {
            self::checkRoleAllowed($id);
            $role = self::selectRoleById($id);
            if(self::countUserRoleByRoleId($id))
                throw new ParametersException(printf('%1$s已分配,不能删除', $role->role_name));
        }
        try {
            DB::beginTransaction();
            // 删除角色与菜单关联
            SysRoleMenu::deleteRoleMenu($roleIds);
            // 删除角色与部门关联
            SysRoleDept::deleteRoleDept($roleIds);
            $row = SysRole::deleteRoleByIds($roleIds);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new ParametersException('删除失败');
        }
        return $row;
    }

    /**
     * 校验角色是否允许操作
     *
     * @param int $roleId 角色信息
     * @return void
     * @throws ParametersException
     */
    function checkRoleAllowed(int $roleId)
    {
        if($roleId == 1)
            throw new ParametersException('不允许操作超级管理员角色');
    }

    /**
     * 通过角色ID查询角色使用数量
     *
     * @param int $roleId 角色ID
     * @return int 结果
     */
    function countUserRoleByRoleId(int $roleId): int
    {
        return SysUserRole::countUserRoleByRoleId($roleId);
    }

    /**
     * 查询所有角色
     *
     * @return mixed 角色列表
     */
    function selectRoleAll()
    {
        // TODO: Implement selectRoleAll() method.
    }
}
