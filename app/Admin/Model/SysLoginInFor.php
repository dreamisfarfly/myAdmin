<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 系统访问日志情况信息 数据层
 *
 * @author zjj
 */
class SysLoginInFor extends BaseModel
{

    protected $table = 'sys_logininfor';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'info_id as infoId',
        'user_name as userName',
        'ipaddr',
        'login_location as loginLocation',
        'browser',
        'os',
        'status',
        'msg',
        'login_time as loginTime'
    ];

    /**
     * 查询系统登录日志集合
     *
     * @param array $queryParam 访问日志对象
     * @return LengthAwarePaginator|null 登录记录集合
     */
    public static function selectLogininforList(array $queryParam): ?LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->when(isset($queryParam['ipaddr']),function($query)use($queryParam){
                    $query->where('ipaddr', 'like', $queryParam['ipaddr'].'%');
                })
                ->when(isset($queryParam['userName']),function($query)use($queryParam){
                    $query->where('user_name', 'like', $queryParam['userName'].'%');
                })
                ->when(isset($queryParam['status']),function($query)use($queryParam){
                    $query->where('status', $queryParam['status']);
                })
                ->when(isset($queryParam['beginTime']),function($query)use($queryParam){
                    $query->where('login_time', '>=', $queryParam['beginTime']);
                })
                ->when(isset($queryParam['endTime']),function($query)use($queryParam){
                    $query->where('login_time', '<=', $queryParam['endTime']);
                })
                ->select(self::SELECT_PARAMS)
        );
    }

    /**
     * 插入系统登录日志
     *
     * @param array $sysLoginInFor
     * @return bool
     */
    public static function insertSysLoginInFor(array $sysLoginInFor): bool
    {
        $sysLoginInFor['loginTime'] = date('Y-m-d H:i:s');
        return self::query()->insert(self::uncamelize($sysLoginInFor));
    }

    /**
     * 批量删除系统登录日志
     *
     * @param array $infoIds
     * @return mixed
     */
    public static function deleteLoginInForByIds(array $infoIds)
    {
        return self::query()->whereIn('info_id', $infoIds)->delete();
    }

    /**
     * 清空系统登录日志
     */
    public static function cleanLoginInFor()
    {
        self::query()->truncate();
    }

}
