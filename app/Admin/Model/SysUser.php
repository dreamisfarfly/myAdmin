<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 用户模型
 *
 * @author zjj
 */
class SysUser extends BaseModel
{

    protected $table = 'sys_user';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'user_id as userId',
        'dept_id as deptId',
        'user_name as userName',
        'nick_name as nickName',
        'user_type as userType',
        'email',
        'phonenumber',
        'sex',
        'avatar',
        'password',
        'status',
        'del_flag as delFlag',
        'login_ip as loginIp',
        'login_date as loginDate',
        'create_by as createBy',
        'create_time as createTime',
        'update_by as updateBy',
        'update_time as updateTime',
        'remark'
    ];

    /**
     * 根据条件分页查询用户数据
     *
     * @return LengthAwarePaginator
     */
    public static function selectUserList(): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
            ->select(self::SELECT_PARAMS)
        );
    }

    /**
     * 根据条件查询用户数据
     *
     * @param string $username
     * @return Builder|Model|object|null
     */
    public static function selectUserByUsername(string $username)
    {
        return self::query()->where('user_name', $username)->select(self::SELECT_PARAMS)->first();
    }

}
