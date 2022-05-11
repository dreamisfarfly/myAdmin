<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Model\SysPost;
use App\Admin\Model\SysUserPost;
use App\Admin\Service\System\ISysPostService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * 岗位信息操作处理
 *
 * @author zjj
 */
class SysPostServiceImpl implements ISysPostService
{

    /**
     * 查询岗位信息集合
     *
     * @param array $queryParam
     * @return LengthAwarePaginator|null
     */
    function selectPostList(array $queryParam): ?LengthAwarePaginator
    {
        return SysPost::selectPostList($queryParam);
    }

    /**
     * 查询所有岗位
     *
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection 岗位列表
     */
    function selectPostAll()
    {
        return SysPost::selectPostAll();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    function selectPostListByUserId(int $userId): Collection
    {
        return SysPost::selectPostListByUserId($userId);
    }

    /**
     * 通过岗位ID查询岗位信息
     *
     * @param int $postId 岗位ID
     * @return Builder|Model|object|null 角色对象信息
     */
    function selectPostById(int $postId)
    {
        return SysPost::selectPostByPost(['postId'=>$postId]);
    }

    /**
     * 校验岗位名称是否唯一
     *
     * @param array $sysPost 岗位信息
     * @param int|null $postId 岗位编号
     * @return string 结果
     */
    function checkPostUnique(array $sysPost, ?int $postId = null): string
    {
        $info = SysPost::selectPostByPost($sysPost);
        if($info != null && $info['postId'] != $postId)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 新增保存岗位信息
     *
     * @param array $sysPost 岗位信息
     * @return bool 结果
     */
    function insertPost(array $sysPost): bool
    {
        return SysPost::insertPost($sysPost);
    }

    /**
     * 修改保存岗位信息
     *
     * @param int $postId 岗位编号
     * @param array $sysPost 岗位信息
     * @return int 结果
     */
    function updatePost(int $postId, array $sysPost): int
    {
        return SysPost::updatePost($postId, $sysPost);
    }

    /**
     * 批量删除岗位信息
     *
     * @param array $ids 需要删除的岗位ID
     * @return mixed 结果
     * @throws ParametersException
     */
    function deletePostByIds(array $ids)
    {
        foreach ($ids as $id)
        {
            $post = self::selectPostById($id);
            if(self::countUserPostById($id) > 0)
            {
                throw new ParametersException($post['postName'].'已分配,不能删除');
            }
        }
        return SysPost::deletePostByIds($ids);
    }

    /**
     * 通过岗位ID查询岗位使用数量
     *
     * @param int $postId 岗位ID
     * @return mixed 结果
     */
    function countUserPostById(int $postId)
    {
        return SysUserPost::countUserPostById($postId);
    }
}
