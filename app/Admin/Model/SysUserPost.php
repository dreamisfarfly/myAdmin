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
     * 添加
     *
     * @param array $userPostList 用户角色列表
     * @return bool 结果
     */
    static function insert(array $userPostList): bool
    {
        return self::query()->insert($userPostList);
    }

    /**
     * 通过用户ID删除用户和岗位关联
     *
     * @param int $userId 用户ID
     * @return mixed 结果
     */
    static function deleteUserPostByUserId(int $userId)
    {
        return self::query()->where('user_id', $userId)->delete();
    }

    /**
     * 批量删除用户和岗位关联
     *
     * @param array $userIds 需要删除的数据ID
     * @return mixed 结果
     */
    static function deleteUserPost(array $userIds)
    {
        return self::query()->whereIn('user_id', $userIds)->delete();
    }

    /**
     * 通过岗位ID查询岗位使用数量
     *
     * @param int $postId 岗位ID
     * @return int 结果
     */
    static function countUserPostById(int $postId): int
    {
        return self::query()->where('post_id', $postId)->count();
    }

}
