<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysPost;
use App\Admin\Service\System\ISysPostService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @return LengthAwarePaginator|null
     */
    function selectPostList(array $queryParam): ?LengthAwarePaginator
    {
        return SysPost::selectPostList($queryParam);
    }
}
