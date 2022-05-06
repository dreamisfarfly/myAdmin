<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\Constants;
use App\Admin\Model\SysConfig;
use App\Admin\Service\System\ISysConfigService;
use Illuminate\Support\Facades\Redis;

/**
 * 参数配置 信息操作处理
 *
 * @author zjj
 */
class SysConfigServiceImpl implements ISysConfigService
{

    /**
     * 根据键名查询参数配置信息
     *
     * @param string $configKey 参数键名
     * @return mixed 参数键值
     */
    function selectConfigByKey(string $configKey)
    {
        $configStr = Redis::get(self::getCacheKey($configKey));
        if($configStr != null){
            $configArray = json_decode($configStr, true);
            return $configArray['configValue'];
        }
        $retConfig = SysConfig::selectConfig(['configKey'=>$configKey]);
        if($retConfig != null)
        {
            Redis::set(self::getCacheKey($configKey), json_encode($retConfig));
            return $retConfig['configValue'];
        }
        return "";
    }

    /**
     * 设置cache key
     *
     * @param string $configKey 参数键
     * @return string 缓存键key
     */
    private function getCacheKey(string $configKey): string
    {
        return Constants::SYS_CONFIG_KEY.$configKey;
    }

}
