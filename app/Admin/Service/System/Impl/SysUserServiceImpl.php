<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Model\SysUser;
use App\Admin\Model\SysUserPost;
use App\Admin\Model\SysUserRole;
use App\Admin\Service\System\ISysUserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 用户
 *
 * @author zjj
 */
class SysUserServiceImpl implements ISysUserService
{

    /**
     * 根据条件分页查询用户列表
     *
     * @param array $queryParam 查询参数
     * @return LengthAwarePaginator
     */
    function selectUserList(array $queryParam): LengthAwarePaginator
    {
        return SysUser::selectUserList($queryParam);
    }

    /**
     * 通过用户ID查询用户
     *
     * @param int $userId 用户ID
     * @return Builder|Model|object|null 用户对象信息
     */
    function selectUserById(int $userId)
    {
        return SysUser::selectUserById($userId);
    }

    /**
     * 校验用户名称是否唯一
     *
     * @param string $userName 用户名称
     * @return string 结果
     */
    function checkUserNameUnique(string $userName): string
    {
        $count = SysUser::checkUserNameUnique($userName);
        if($count > 0)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 检测指定条件是否唯一
     *
     * @param array $user
     * @param int|null $userId
     * @return string
     */
    function checkAssignUnique(array $user, ?int $userId = null): string
    {
        $info = SysUser::selectUserByUser($user);
        if($info != null && $info['userId'] != $userId)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 新增用户信息
     *
     * @param array $user 用户信息
     * @return int 结果
     */
    function insertUser(array $user): int
    {
        $userId = 0;
        try {
            DB::beginTransaction();
            // 新增用户信息
            $userId = SysUser::insertUser($user);
            // 新增用户岗位关联
            isset($user['postIds'])?$postIds=$user['postIds']:$postIds=[];
            self::insertUserPost($postIds,$userId);
            // 新增用户与角色管理
            isset($user['roleIds'])?$roleIds=$user['roleIds']:$roleIds=[];
            self::insertUserRole($roleIds,$userId);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
        }
        return $userId;
    }

    /**
     * 新增用户岗位信息
     *
     * @param array $postIds 岗位数组
     * @param int $userId 用户编号
     * @return void
     */
    function insertUserPost(array $postIds, int $userId)
    {
        $data = [];
        foreach ($postIds as $item)
        {
            array_push($data,[
                'user_id' => $userId,
                'post_id' => $item
            ]);
        }
        if(count($data) > 0)
        {
            SysUserPost::insert($data);
        }
    }

    /**
     * 新增用户角色信息
     *
     * @param array $roleIds 角色数组
     * @param int $userId 用户编号
     */
    function insertUserRole(array $roleIds, int $userId)
    {
        $data = [];
        foreach ($roleIds as $item)
        {
            array_push($data,[
                'user_id' => $userId,
                'role_id' => $item
            ]);
        }
        if(count($data) > 0)
        {
            SysUserRole::insert($data);
        }
    }
}
