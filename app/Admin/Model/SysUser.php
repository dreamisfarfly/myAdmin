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
     * @return LengthAwarePaginator
     */
    public static function selectUserList(): LengthAwarePaginator
    {
        return self::query()->paginate();
    }

}
