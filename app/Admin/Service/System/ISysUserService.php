<?php

namespace App\Admin\Service\System;

/**
 * 用户接口
 *
 * @author zjj
 */
interface ISysUserService
{

    /**
     * 根据条件分页查询用户列表
     *
     * @param array $queryParam 查询参数
     * @return mixed
     */
    function selectUserList(array $queryParam);

    /**
     * 查询用户信息
     *
     * @param array $sysUser
     * @return mixed
     */
    function selectUserByUserName(array $sysUser);

    /**
     * 通过用户ID查询用户
     *
     * @param int $userId 用户ID
     * @return mixed 用户对象信息
     */
    function selectUserById(int $userId);

    /**
     * 校验用户名称是否唯一
     *
     * @param string $userName 用户名称
     * @return mixed 结果
     */
    function checkUserNameUnique(string $userName);

    /**
     * 检测指定条件是否唯一
     *
     * @param array $user
     * @param int|null $userId
     * @return mixed
     */
    function checkAssignUnique(array $user, ?int $userId = null);

    /**
     * 新增用户信息
     *
     * @param array $user 用户信息
     * @return mixed 结果
     */
    function insertUser(array $user);

    /**
     * 新增用户岗位信息
     *
     * @param array $postIds 岗位数组
     * @param int $userId 用户编号
     * @return mixed
     */
    function insertUserPost(array $postIds, int $userId);

    /**
     * 校验用户是否允许操作
     *
     * @param ?int $userId 用户编号
     * @return mixed
     */
    function checkUserAllowed(?int $userId = null);

    /**
     * 修改用户信息
     *
     * @param int $userId 用户编号
     * @param array $user 用户信息
     * @return mixed 结果
     */
    function updateUser(int $userId, array $user);

    /**
     * 更该用户状态信息
     *
     * @param int $userId 用户编号
     * @param array $user 用户信息
     * @return mixed 结果
     */
    function updateUserStatus(int $userId, array $user);

    /**
     * 批量删除用户信息
     *
     * @param array $ids 需要删除的用户ID
     * @return mixed 结果
     */
    function deleteUserByIds(array $ids);

    /**
     * 更改密码
     * @param int $userId
     * @param array $user
     * @return mixed
     */
    function resetPwd(int $userId, array $user);

    /**
     * 根据用户ID查询用户所属角色组
     *
     * @param string $userName 用户名
     * @return mixed 结果
     */
    function selectUserRoleGroup(string $userName);

    /**
     * 根据用户ID查询用户所属岗位组
     *
     * @param string $userName 用户名
     * @return mixed 结果
     */
    function selectUserPostGroup(string $userName);

    /**
     * 更改用户信息
     *
     * @param int $userId 用户编号
     * @param array $sysUser 用户信息
     * @return mixed 结果
     */
    function updateUserProfile(int $userId, array $sysUser);

}
