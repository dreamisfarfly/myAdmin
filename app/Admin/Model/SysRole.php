<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * 角色表
 *
 * @author zjj
 */
class SysRole extends BaseModel
{

    protected $table = 'sys_role';

    /**
     * 根据条件分页查询角色数据
     *
     * @return LengthAwarePaginator
     */
    static function selectRoleList(): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
              ->select([
                  'role_id as roleId',
                  'role_name as roleName',
                  'role_key as roleKey',
                  'role_sort as roleSort',
                  'data_scope as dataScope',
                  'menu_check_strictly as menuCheckStrictly',
                  'dept_check_strictly as deptCheckStrictly',
                  'status',
                  'del_flag as delFlag',
                  'create_by as createBy',
                  'create_time as createTime',
                  'update_by as updateBy',
                  'update_time as updateTime',
                  'remark'
              ])
        );
    }

    /**
     * 批量删除角色信息
     *
     * @param array $roleIds 需要删除的角色ID
     * @return bool 结果
     */
    static function deleteRoleByIds(array $roleIds): bool
    {
        return self::query()
            ->where('role_id', $roleIds)
            ->exists();
    }

}
