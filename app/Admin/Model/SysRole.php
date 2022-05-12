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
                ->where('r.del_flag', 0)
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
     * 查询全部角色
     * @return Builder[]|Collection
     */
    static function selectRoleAll()
    {
        return self::query()
            ->from('sys_role as r')
            ->where('r.del_flag', 0)
            ->select(self::SELECT_PARAMS)
            ->get();
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
     * 根据用户ID获取角色选择框列表
     *
     * @param int $userId 用户ID
     * @return \Illuminate\Support\Collection 选中角色ID列表
     */
    public static function selectRoleListByUserId(int $userId): \Illuminate\Support\Collection
    {
        return self::query()
            ->from('sys_role as r')
            ->leftJoin('sys_user_role as ur', function($query){
                $query->on('ur.role_id', '=', 'r.role_id');
            })
            ->leftJoin('sys_user as u', function($query){
                $query->on('u.user_id', '=', 'ur.user_id');
            })
            ->where('u.user_id', $userId)
            ->pluck('r.role_id');
    }

    /**
     * 根据条件查询系统角色
     *
     * @param array $sysRole 角色信息
     * @return Builder|Model|mixed|object|null 结果
     */
    public static function selectRoleByRole(array $sysRole)
    {
        return self::query()
            ->from('sys_role as r')
            ->when(isset($sysRole['roleName']),function($query)use($sysRole){
                $query->where('r.role_name', $sysRole['roleName']);
            })
            ->when(isset($sysRole['roleKey']),function($query)use($sysRole){
                $query->where('r.role_key', $sysRole['roleKey']);
            })
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 新增角色信息
     *
     * @param array $sysRole 角色信息
     * @return int 结果
     */
    public static function insertRole(array $sysRole): int
    {
        $sysRole['createTime'] = date('Y-m-d H:i:s');
        return self::query()->insertGetId(self::uncamelize($sysRole,['menuIds','deptIds']));
    }

    /**
     * 根据用户ID查询角色
     *
     * @param string $userName 用户名
     * @return Builder[]|Collection 角色列表
     */
    public static function selectRolesByUserName(string $userName)
    {
        return self::selectRoleVo()
            ->where('r.del_flag', 0)
            ->where('u.user_name', $userName)
            ->get();
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

    /**
     * 修改角色信息
     *
     * @param int $roleId 角色编号
     * @param array $sysRole 角色
     * @return int 结果
     */
    public static function updateRole(int $roleId, array $sysRole): int
    {
        $sysRole['updateTime'] = date('Y-m-d H:i:s');
        return self::query()->where('role_id', $roleId)
            ->update(self::uncamelize($sysRole,['roleId','menuIds','deptIds']));
    }

    /**
     * 是不是管理员角色
     * @param int|null $roleId
     * @return bool
     */
    public static function isAdmin(?int $roleId): bool
    {
        return 1 == $roleId;
    }

}
