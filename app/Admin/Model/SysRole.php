<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
     * @param array $queryParam 查询参数
     * @return LengthAwarePaginator
     */
    static function selectRoleList(array $queryParam): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->when(isset($queryParam['roleName']),function($query)use($queryParam){
                  $query->where('role_name', 'like', $queryParam['roleName'].'%');
                })->when(isset($queryParam['roleKey']),function($query)use($queryParam){
                  $query->where('role_key', 'like', $queryParam['roleKey'].'%');
                })
                ->when(isset($queryParam['status']),function($query)use($queryParam){
                  $query->where('status', $queryParam['status']);
                })
                ->when(isset($queryParam['beginTime']),function($query)use($queryParam){
                    $query->where('create_time', '>=', $queryParam['beginTime']);
                })
                ->when(isset($queryParam['endTime']),function($query)use($queryParam){
                    $query->where('create_time', '<=', $queryParam['endTime']);
                })
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
    public static function deleteRoleByIds(array $roleIds): bool
    {
        return self::query()
            ->where('role_id', $roleIds)
            ->exists();
    }

    /**
     * 根据用户ID查询角色
     *
     * @param int $userId
     * @return Builder[]|Collection
     */
    public static function selectRolePermissionByUserId(int $userId)
    {
        return self::query()
            ->from('sys_role as r')
            ->leftJoin('sys_user_role as ur',function($query){
                $query->on('ur.role_id', '=', 'r.role_id');
            })
            ->leftJoin('sys_user as u',function ($query){
                $query->on('u.user_id', '=', 'ur.user_id');
            })
            ->leftJoin('sys_dept as d',function($query){
                $query->on('d.dept_id', '=', 'u.dept_id');
            })
            ->where('r.del_flag', 0)
            ->where('ur.user_id', $userId)
            ->select([
                'r.role_id as roleId',
                'r.role_name as roleName',
                'r.role_key as roleKey',
                'r.role_sort as roleSort',
                'r.data_scope as dataScope',
                'r.menu_check_strictly as menuCheckStrictly',
                'r.dept_check_strictly as deptCheckStrictly',
                'r.status',
                'r.del_flag as delFlag',
                'r.create_time as createTime',
                'r.remark'
            ])
            ->get();
    }

}
