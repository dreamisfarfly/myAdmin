<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Model\SysOperateLog;
use App\Admin\Service\System\ISysOperLogService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 操作日志 服务层
 *
 * @author zjj
 */
class SysOperLogServiceImpl implements ISysOperLogService
{

    /**
     * 查询系统操作日志集合
     *
     * @param array $queryParam 操作日志对象
     * @return LengthAwarePaginator|null 操作日志集合
     */
    function selectOperLogList(array $queryParam): ?LengthAwarePaginator
    {
        $dataList = SysOperateLog::selectOperLogList($queryParam);
        foreach ($dataList as $item){
            $item->jsonResult = json_decode($item->jsonResult,true);
            $item->operParam = json_decode($item->operParam,true);
        }
        return $dataList;
    }

    /**
     * 批量删除系统操作日志
     *
     * @param array $ids 需要删除的操作日志ID
     * @return mixed 结果
     */
    function deleteOperLogByIds(array $ids)
    {
        return SysOperateLog::deleteOperLogByIds($ids);
    }

    /**
     * 清空操作日志
     *
     * @return mixed
     */
    function cleanOperLog()
    {
        SysOperateLog::cleanOperLog();
    }
}
