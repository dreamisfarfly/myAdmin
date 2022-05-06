<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
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
     * 查询参数配置列表
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

}
