<?php

namespace App\Admin\Core\Constant;

/**
 * 通用常量信息
 *
 * @author zjj
 */
interface Constants
{

    /**
     * 字典管理 cache key
     */
    const SYS_DICT_KEY = 'sys_dict:';

    /**
     * 登录用户 redis key
     */
    const LOGIN_TOKEN_KEY = 'login_tokens:';

    /**
     * 令牌前缀
     */
    const LOGIN_USER_KEY = 'login_user_key';

    /**
     * 令牌前缀
     */
    const TOKEN_PREFIX = "Bearer ";

}
