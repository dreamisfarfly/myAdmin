<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
    public static function selectRoleList(): LengthAwarePaginator
    {
        return self::query()->paginate();
    }

}
