<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysUser;
use App\Admin\Service\System\ISysUserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
}
