<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
     * 查询参数
     */
    protected const SELECT_PARAMS = [
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
    ];

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
                ->from('sys_role as r')
                ->when(isset($queryParam['roleName']),function($query)use($queryParam){
                  $query->where('r.role_name', 'like', $queryParam['roleName'].'%');
                })->when(isset($queryParam['roleKey']),function($query)use($queryParam){
                  $query->where('r.role_key', 'like', $queryParam['roleKey'].'%');
                })
                ->when(isset($queryParam['status']),function($query)use($queryParam){
                  $query->where('r.status', $queryParam['status']);
                })
                ->when(isset($queryParam['beginTime']),function($query)use($queryParam){
                    $query->where('r.create_time', '>=', $queryParam['beginTime']);
                })
                ->when(isset($queryParam['endTime']),function($query)use($queryParam){
                    $query->where('r.create_time', '<=', $queryParam['endTime']);
                })
                ->select(self::SELECT_PARAMS)
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
        return self::selectRoleVo()
            ->where('r.del_flag', 0)
            ->where('ur.user_id', $userId)
            ->get();
    }

    /**
     * 通过角色ID查询角色
     *
     * @param int $roleId 角色ID
     * @return Builder|Model|object|null 角色对象信息
     */
    public static function selectRoleById(int $roleId)
    {
        return self::selectRoleVo()->where('r.role_id', $roleId)->first();
    }

    /**
     * 查询
     * @return Builder
     */
    private static function selectRoleVo(): Builder
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
            ->select(self::SELECT_PARAMS);
    }

}
