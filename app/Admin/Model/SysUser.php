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
     * @param array $queryParam
     * @return LengthAwarePaginator
     */
    public static function selectUserList(array $queryParam = []): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->from('sys_user as u')
                ->leftJoin('sys_dept as d', function($query){
                    $query->on('u.dept_id', '=', 'd.dept_id');
                })
                ->when(isset($queryParam['userName']),function($query) use($queryParam){
                    $query->where('u.user_name', 'like', $queryParam['userName'].'%');
                })
                ->when(isset($queryParam['status']),function($query) use($queryParam){
                    $query->where('u.status', $queryParam['status']);
                })
                ->when(isset($queryParam['beginTime']),function($query) use($queryParam){
                    $query->where('u.create_time', '>=', $queryParam['beginTime']);
                })
                ->when(isset($queryParam['endTime']),function($query) use($queryParam){
                    $query->where('u.create_time', '<=', $queryParam['endTime']);
                })
                ->when(isset($queryParam['phonenumber']),function($query) use($queryParam){
                    $query->where('u.phonenumber', 'like', $queryParam['phonenumber'].'%');
                })
                ->when(isset($queryParam['deptId']) && $queryParam['deptId'] != 0,function($query) use($queryParam){
                    $query->where(function($query) use($queryParam){
                        $query->where('u.dept_id', $queryParam['deptId'])
                            ->orWhereIn('u.dept_id',function($query) use($queryParam){
                                $query->select('t.dept_id')->from('sys_dept as t')
                                    ->whereRaw('find_in_set(?,ancestors)',$queryParam['deptId']);
                            });
                    });
                })
                ->select([
                    'u.user_id as userId',
                    'u.dept_id as deptId',
                    'u.nick_name as nickName',
                    'u.user_name as userName',
                    'u.email',
                    'u.avatar',
                    'u.phonenumber',
                    'u.password',
                    'u.sex',
                    'u.status',
                    'u.del_flag as delFlag',
                    'u.login_ip as loginIp',
                    'u.login_date as loginDate',
                    'u.create_by as createBy',
                    'u.create_time as createTime',
                    'u.remark',
                    'd.dept_name as deptName',
                    'd.leader'
                ])
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

    /**
     * 通过用户ID查询用户
     *
     * @param int $userId 用户ID
     * @return Builder|Model|object|null 用户对象信息
     */
    public static function selectUserById(int $userId)
    {
        return self::selectUserVo()
            ->where('u.user_id', $userId)
            ->first();
    }

    /**
     * 查询链接
     * @return Builder
     */
    private static function selectUserVo(): Builder
    {
        return self::query()
            ->from('sys_user as u')
            ->leftJoin('sys_dept as d',function($query){
                $query->on('u.dept_id', '=', 'd.dept_id');
            })
            ->leftJoin('sys_user_role as ur',function($query){
                $query->on('u.user_id', '=', 'ur.user_id');
            })
            ->leftJoin('sys_role as r',function($query){
                $query->on('r.role_id', '=', 'ur.role_id');
            })
            ->select([
                'u.user_id as userId',
                'u.dept_id as deptId',
                'u.user_name as userName',
                'u.nick_name as nickName',
                'u.email',
                'u.avatar',
                'u.phonenumber',
                'u.password',
                'u.sex',
                'u.status',
                'u.del_flag as delFlag',
                'u.login_ip as loginIp',
                'u.login_date as loginDate',
                'u.create_by as createBy',
                'u.create_time as createTime',
                'u.remark',
                'd.dept_id as deptId',
                'd.parent_id as parentId',
                'd.dept_name as deptName',
                'd.order_num as orderNum',
                'd.leader',
                'd.status as deptStatus',
                'r.role_id as roleId',
                'r.role_name as roleName',
                'r.role_key as roleKey',
                'r.role_sort as roleSort',
                'r.data_scope as dataScope',
                'r.status as roleStatus'
            ]);
    }

}
