<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 操作日志 数据层
 *
 * @author ruoyi
 */
class SysOperateLog extends BaseModel
{

    protected $table = 'sys_oper_log';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'oper_id as operId',
        'title',
        'business_type as businessType',
        'method',
        'request_method as requestMethod',
        'operator_type as operatorType',
        'oper_name as operName',
        'dept_name as deptName',
        'oper_url as operUrl',
        'oper_ip as operIp',
        'oper_location as operLocation',
        'oper_param as operParam',
        'json_result as jsonResult',
        'status',
        'error_msg as errorMsg',
        'oper_time as operTime'
    ];

    /**
     * 查询系统操作日志集合
     *
     * @param array $queryParam 操作日志对象
     * @return LengthAwarePaginator|null 操作日志集合
     */
    public static function selectOperLogList(array $queryParam): ?LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
            ->select(self::SELECT_PARAMS)
        );
    }

    /**
     * 批量删除系统操作日志
     *
     * @param array $ids 需要删除的操作日志ID
     * @return mixed 结果
     */
    public static function deleteOperLogByIds(array $ids)
    {
        return self::query()->whereIn('oper_id', $ids)->delete();
    }

    /**
     * 清空日志
     */
    public static function cleanOperLog()
    {
        self::query()->truncate();
    }

}
