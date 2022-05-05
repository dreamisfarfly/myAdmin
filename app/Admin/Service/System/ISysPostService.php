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

}
