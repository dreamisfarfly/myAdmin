<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysPost;
use App\Admin\Service\System\ISysPostService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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
}
