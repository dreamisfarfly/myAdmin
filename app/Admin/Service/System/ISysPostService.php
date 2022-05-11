<?php

namespace App\Admin\Service\System;

/**
 * 岗位信息操作处理
 *
 * @author zjj
 */
interface ISysPostService
{

    /**
     * 查询岗位信息集合
     *
     * @return mixed
     */
    function selectPostList(array $queryParam);

    /**
     * 查询所有岗位
     *
     * @return mixed 岗位列表
     */
    function selectPostAll();

    /**
     * 根据用户编号查询岗位
     * @param int $userId
     * @return mixed
     */
    function selectPostListByUserId(int $userId);

    /**
     * 通过岗位ID查询岗位信息
     *
     * @param int $postId 岗位ID
     * @return mixed 角色对象信息
     */
    function selectPostById(int $postId);

    /**
     * 校验岗位名称是否唯一
     *
     * @param array $sysPost 岗位信息
     * @param int|null $postId 岗位编号
     * @return mixed 结果
     */
    function checkPostUnique(array $sysPost, ?int $postId = null);

    /**
     * 新增保存岗位信息
     *
     * @param array $sysPost 岗位信息
     * @return mixed 结果
     */
    function insertPost(array $sysPost);

    /**
     * 修改保存岗位信息
     *
     * @param int $postId 岗位编号
     * @param array $sysPost 岗位信息
     * @return mixed 结果
     */
    function updatePost(int $postId, array $sysPost);

    /**
     * 批量删除岗位信息
     *
     * @param array $ids 需要删除的岗位ID
     * @return mixed 结果
     */
    function deletePostByIds(array $ids);

    /**
     * 通过岗位ID查询岗位使用数量
     *
     * @param int $postId 岗位ID
     * @return mixed 结果
     */
    function countUserPostById(int $postId);

}
