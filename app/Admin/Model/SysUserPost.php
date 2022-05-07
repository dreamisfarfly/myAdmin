<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;

/**
 * 用户与岗位关联表 数据层
 *
 * @author zjj
 */
class SysUserPost extends BaseModel
{

    protected $table = 'sys_user_post';

    /**
     * batchUserPost
     *
     * @param array $userPostList 用户角色列表
     * @return bool 结果
     */
    static function insert(array $userPostList): bool
    {
        return self::query()->insert($userPostList);
    }

}
