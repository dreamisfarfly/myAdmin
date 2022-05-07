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

}
