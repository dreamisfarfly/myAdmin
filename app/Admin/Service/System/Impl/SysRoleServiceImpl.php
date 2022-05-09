<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Model\SysRole;
use App\Admin\Model\SysRoleDept;
use App\Admin\Model\SysRoleMenu;
use App\Admin\Model\SysUserRole;
use App\Admin\Service\System\ISysRoleService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 角色服务接口实现
 */
class SysRoleServiceImpl implements ISysRoleService
{

    /**
     * 根据条件分页查询角色数据
     *
     * @param array $queryParam 查询参数
     * @return LengthAwarePaginator
     */
    public function selectRoleList(array $queryParam): LengthAwarePaginator
    {
        return SysRole::selectRoleList($queryParam);
    }

    /**
     * 通过角色ID查询角色
     *
     * @param int $roleId 角色ID
     * @return Builder|Model|object|null 角色对象信息
     */
    function selectRoleById(int $roleId)
    {
        return SysRole::selectRoleById($roleId);
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
        }catch (Exception $exception){
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
     * @return Builder[]|Collection 角色列表
     */
    function selectRoleAll()
    {
        return SysRole::selectRoleAll();
    }

    /**
     * 根据用户ID获取角色选择框列表
     *
     * @param int $userId 用户ID
     * @return \Illuminate\Support\Collection 选中角色ID列表
     */
    function selectRoleListByUserId(int $userId): \Illuminate\Support\Collection
    {
        return SysRole::selectRoleListByUserId($userId);
    }

    /**
     * 修改角色状态
     *
     * @param int $roleId 角色编号
     * @param array $sysRole 角色信息
     * @return int 结果
     */
    function updateRoleStatus(int $roleId, array $sysRole): int
    {
        return SysRole::updateRole($roleId, $sysRole);
    }

    /**
     * 校验角色权限是否唯一
     *
     * @param array $sysRole 角色信息
     * @param int|null $roleId 角色编号
     * @return string 结果
     */
    function checkAssignUnique(array $sysRole, ?int $roleId = null): string
    {
        $info = SysRole::selectRoleByRole($sysRole);
        if($info != null && $info['roleId'] != $roleId)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 新增保存角色信息
     *
     * @param array $sysRole 角色信息
     * @return int 结果
     */
    function insertRole(array $sysRole): int
    {
        $row = 0;
        try {
            DB::beginTransaction();
            // 新增角色信息
            $sysRoleId = SysRole::insertRole($sysRole);
            $row = self::insertRoleMenu($sysRoleId, $sysRole['menuIds']);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            Log::info($exception->getMessage());
        }
        return $row;
    }

    /**
     * 新增角色菜单信息
     *
     * @param int $sysRoleId 角色编号
     * @param array $menusArr 角色
     * @return int
     */
    function insertRoleMenu(int $sysRoleId, array $menusArr): int
    {
        $row = 1;
        $temp = [];
        foreach ($menusArr as $item)
        {
            array_push($temp,[
                'role_id' => $sysRoleId,
                'menu_id' => $item
            ]);
        }
        if(count($temp) > 0)
        {
            $row = SysRoleMenu::insertRoleMenu($temp);
        }
        return $row;
    }

}
