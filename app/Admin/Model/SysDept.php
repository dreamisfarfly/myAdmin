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
     * 查询部门是否存在用户
     *
     * @param int $deptId
     * @return int
     */
    public static function checkDeptExistUser(int $deptId): int
    {
        return self::query()
            ->where('dept_id', $deptId)
            ->where('del_flag', 0)
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
     * @return Builder[]|Collection
     */
    public static function selectDeptList()
    {
        return self::query()
            ->where('del_flag', 0)
            ->select(self::SELECT_PARAMS)
            ->orderBy('parent_id')
            ->orderBy('order_num')
            ->get();
    }

}
