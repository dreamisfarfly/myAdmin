<?php

namespace App\Admin\Service\System;

/**
 * 操作日志 服务层
 *
 * @author zjj
 */
interface ISysOperLogService
{

    /**
     * 查询系统操作日志集合
     *
     * @param array $queryParam 操作日志对象
     * @return mixed 操作日志集合
     */
    function selectOperLogList(array $queryParam);

    /**
     * 批量删除系统操作日志
     *
     * @param array $ids 需要删除的操作日志ID
     * @return mixed 结果
     */
    function deleteOperLogByIds(array $ids);

    /**
     * 清空操作日志
     *
     * @return mixed
     */
    function cleanOperLog();

}
