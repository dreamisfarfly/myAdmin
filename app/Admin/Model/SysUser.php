<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 用户模型
 *
 * @author zjj
 */
class SysUser extends BaseModel
{

    protected $table = 'sys_user';

    /**
     * 根据条件分页查询用户数据
     *
     * @return LengthAwarePaginator
     */
    public static function selectUserList(): LengthAwarePaginator
    {
        return self::customPagination(self::query());
    }

}
