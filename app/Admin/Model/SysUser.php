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

    protected $primaryKey = 'user_id';

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
     * 校验用户名称是否唯一
     *
     * @param string $userName 用户名称
     * @return int 结果
     */
    public static function checkUserNameUnique(string $userName): int
    {
        return self::query()
            ->where('user_name', $userName)
            ->limit(1)
            ->count();
    }

    /**
     * 根据条件查询系统用户
     *
     * @param array $user
     * @return Builder|Model|mixed|object|null
     */
    public static function selectUserByUser(array $user)
    {
        return self::query()
            ->when(isset($user['phonenumber']),function($query)use($user){
                $query->where('phonenumber', $user['phonenumber']);
            })
            ->when(isset($user['email']),function($query)use($user){
                $query->where('email', $user['email']);
            })
            ->when(isset($user['userName']),function($query)use($user){
                $query->where('user_name', $user['userName']);
            })
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 新增用户信息
     *
     * @param array $user 用户信息
     * @return int 结果
     */
    public static function insertUser(array $user): int
    {
        $user['createTime'] = date('Y-m-d H:i:s');
        return self::query()->insertGetId(self::uncamelize($user,['roleIds','postIds']));
    }

    /**
     * 修改用户信息
     *
     * @param int $userId 用户编号
     * @param array $user 用户信息
     * @return int
     */
    public static function updatetUser(int $userId, array $user): int
    {
        $user['updateTime'] = date('Y-m-d H:i:s');
        return self::query()
            ->where('user_id',$userId)
            ->update(self::uncamelize($user,['roleIds','postIds', 'userId']));
    }

    /**
     * 获取加密密码
     * @param string $password
     * @return string
     */
    public static function getPassword(string $password): string
    {
        return md5($password);
    }

    /**
     * 批量删除用户信息
     *
     * @param array $userIds 需要删除的用户ID
     * @return mixed 结果
     */
    public static function deleteUserByIds(array $userIds)
    {
        return self::query()->whereIn('user_id', $userIds)->delete();
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
