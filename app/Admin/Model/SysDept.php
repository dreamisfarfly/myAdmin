<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * 部门管理 数据层
 *
 * @author zjj
 */
class SysDept extends BaseModel
{

    protected $table = 'sys_dept';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'dept_id as deptId',
        'parent_id as parentId',
        'ancestors',
        'dept_name as deptName',
        'order_num as orderNum',
        'leader',
        'phone',
        'email',
        'status',
        'del_flag as delFlag',
        'create_by as createBy',
        'create_time as createTime'
    ];

    /**
     * 是否存在子节点
     *
     * @param int $deptId 部门ID
     * @return int 结果
     */
    public static function hasChildByDeptId(int $deptId): int
    {
        return self::query()
            ->where('del_flag', 0)
            ->where('parent_id', $deptId)
            ->count();
    }

    /**
     * 删除部门管理信息
     *
     * @param int $deptId 部门ID
     * @return int 结果
     */
    public static function deleteDeptById(int $deptId): int
    {
        return self::query()
            ->where('dept_id', $deptId)
            ->update([
                'del_flag' => 2
            ]);
    }

    /**
     * 查询部门管理数据
     * @param array $queryParams 查询参数
     * @return Builder[]|Collection
     */
    public static function selectDeptList(array $queryParams)
    {
        return self::query()
            ->when(isset($queryParams['deptName']),function($query)use($queryParams){
                $query->where('dept_name', 'like', $queryParams['deptName'].'%');
            })
            ->when(isset($queryParams['status']),function($query)use($queryParams){
                $query->where('status', $queryParams['status']);
            })
            ->where('del_flag', 0)
            ->select(self::SELECT_PARAMS)
            ->orderBy('parent_id')
            ->orderBy('order_num')
            ->get();
    }

    /**
     * 根据部门ID查询信息
     *
     * @param int $deptId 部门ID
     * @return Builder|Model|object|null 部门信息
     */
    public static function selectDeptById(int $deptId)
    {
        return self::query()
            ->where('dept_id', $deptId)
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 校验部门名称是否唯一
     *
     * @param array $sysDept 部门名称
     * @return Builder|Model|object|null 结果
     */
    public static function checkDeptUnique(array $sysDept)
    {
        return self::query()
            ->when(isset($sysDept['deptName']),function($query)use($sysDept){
                $query->where('dept_name', $sysDept['deptName']);
            })
            ->when(isset($sysDept['parentId']),function($query)use($sysDept){
                $query->where('parent_id', $sysDept['parentId']);
            })
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 新增部门信息
     *
     * @param array $sysDept 部门信息
     * @return bool 结果
     */
    public static function insertDept(array $sysDept): bool
    {
        $sysDept['createTime'] = date('Y-m-d H:i:s');
        return self::query()->insert(self::uncamelize($sysDept));
    }

    /**
     * 根据ID查询所有子部门（正常状态）
     *
     * @param int $deptId 部门ID
     * @return Builder[]|Collection 子部门数
     */
    public static function selectNormalChildrenDeptById(int $deptId)
    {
        return self::query()
            ->where('status', 0)
            ->where('del_flag', 0)
            ->whereRaw('find_in_set(?,ancestors)', [$deptId])
            ->select(self::SELECT_PARAMS)
            ->get();
    }

    /**
     * 根据ID查询所有子部门
     *
     * @param int $deptId 部门ID
     * @return Builder[]|Collection 部门列表
     */
    public static function selectChildrenDeptById(int $deptId)
    {
        return self::query()
            ->whereRaw('find_in_set(?,ancestors)', [$deptId])
            ->select(self::SELECT_PARAMS)
            ->get();
    }

    /**
     * 更新部门
     *
     * @param int $deptId
     * @param array $sysDept
     * @return int
     */
    public static function updateDept(int $deptId, array $sysDept): int
    {
        $sysDept['updateTime'] = date('Y-m-d H:i:s');
        return self::query()->where('dept_id',$deptId)->update(self::uncamelize($sysDept));
    }

    /**
     * @param array $ancestors
     * @param array $sysDept
     * @return int
     */
    public static function updateDeptStatus(array $ancestors, array $sysDept): int
    {
        $sysDept['updateTime'] = date('Y-m-d H:i:s');
        return self::query()
            ->whereIn('dept_id', $ancestors)
            ->update(self::uncamelize($sysDept));
    }

}
