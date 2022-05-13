<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 参数配置 数据层
 *
 * @author zjj
 */
class SysConfig extends BaseModel
{

    protected $table = 'sys_config';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'config_id as configId',
        'config_name as configName',
        'config_key as configKey',
        'config_value as configValue',
        'config_type as configType',
        'create_by as createBy',
        'create_time as createTime',
        'update_by as updateBy',
        'update_time as updateTime',
        'remark'
    ];

    /**
     * 查询参数配置详情
     * @param array $config
     * @return Builder|Model|mixed|object|null
     */
    public static function selectConfig(array $config)
    {
        return self::query()
            ->when(isset($config['configId']),function ($query)use($config){
                $query->where('config_id',$config['configId']);
            })
            ->when(isset($config['configKey']),function ($query)use($config){
                $query->where('config_key',$config['configKey']);
            })
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 查询参数配置列表
     *
     * @param array $queryParam 参数配置信息
     * @return LengthAwarePaginator|null 参数配置集合
     */
    public static function selectConfigList(array $queryParam): ?LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->when(isset($queryParam['configName']),function($query)use($queryParam){
                    $query->where('config_name', 'like', $queryParam['configName'].'%');
                })
                ->when(isset($queryParam['configKey']),function($query)use($queryParam){
                    $query->where('config_key', 'like', $queryParam['configKey'].'%');
                })
                ->when(isset($queryParam['configType']),function($query)use($queryParam){
                    $query->where('config_type', $queryParam['configType']);
                })
                ->when(isset($queryParam['beginTime']),function($query)use($queryParam){
                    $query->where('create_time', '>=', $queryParam['beginTime']);
                })
                ->when(isset($queryParam['endTime']),function($query)use($queryParam){
                    $query->where('create_time', '<=', $queryParam['endTime']);
                })
                ->select(self::SELECT_PARAMS)
        );
    }

    /**
     * 新增参数配置
     *
     * @param array $sysConfig 参数配置信息
     * @return bool 结果
     */
    public static function insertConfig(array $sysConfig): bool
    {
        $sysConfig['createTime'] = date('Y-m-d H:i:s');
        return self::query()->insert(self::uncamelize($sysConfig));
    }

    /**
     * 批量删除参数信息
     *
     * @param array $configIds 需要删除的参数ID
     * @return mixed 结果
     */
    public static function deleteConfigByIds(array $configIds)
    {
        return self::query()->whereIn('config_id', $configIds)->delete();
    }

    /**
     * 修改参数配置
     *
     * @param int $configId 参数配置编号
     * @param array $sysConfig 参数配置信息
     * @return int 结果
     */
    public static function updateConfig(int $configId, array $sysConfig): int
    {
        $sysConfig['updateTime'] = date('Y-m-d H:i:s');
        return self::query()->where('config_id', $configId)->update(self::uncamelize($sysConfig));
    }

}
