<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysLoginInFor;
use App\Admin\Service\System\ISysLoginInForService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 系统访问日志情况信息 服务层
 *
 * @author zjj
 */
class SysLoginInForServiceImpl implements ISysLoginInForService
{

    /**
     * 查询系统登录日志集合
     *
     * @param array $queryParam 访问日志对象
     * @return LengthAwarePaginator|null 登录记录集合
     */
    function selectLoginInForList(array $queryParam): ?LengthAwarePaginator
    {
        return SysLoginInFor::selectLogininforList($queryParam);
    }

    /**
     * 批量删除系统登录日志
     *
     * @param array $infoIds 需要删除的登录日志ID
     * @return mixed 结果
     */
    function deleteLoginInForByIds(array $infoIds)
    {
        return SysLoginInFor::deleteLoginInForByIds($infoIds);
    }

    /**
     * 清空系统登录日志
     *
     * @return void
     */
    function cleanLoginInFor()
    {
        SysLoginInFor::cleanLoginInFor();
    }
}
