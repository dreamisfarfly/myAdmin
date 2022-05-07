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

}
