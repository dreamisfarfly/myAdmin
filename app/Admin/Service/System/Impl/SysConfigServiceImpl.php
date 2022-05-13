<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\Constants;
use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Model\SysConfig;
use App\Admin\Service\System\ISysConfigService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
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

    /**
     * 查询参数配置列表
     *
     * @param array $queryParam 参数配置信息
     * @return LengthAwarePaginator|null 参数配置集合
     */
    function selectConfigList(array $queryParam): ?LengthAwarePaginator
    {
        return SysConfig::selectConfigList($queryParam);
    }

    /**
     * 根据参数编号获取详细信息
     *
     * @param int $configId 参数配置ID
     * @return mixed 参数配置信息
     */
    function selectConfigById(int $configId)
    {
        return SysConfig::selectConfig(['configId'=>$configId]);
    }

    /**
     * 校验参数键名是否唯一
     *
     * @param string $configKey 参数配置key
     * @param ?int $configId 参数配置编号
     * @return string
     */
    function checkConfigKeyUnique(string $configKey, ?int $configId = null): string
    {
        $info = SysConfig::selectConfig(['configKey' => $configKey]);
        if($info != null && $configId != $info['configId'])
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }

    /**
     * 新增参数配置
     *
     * @param array $sysConfig 参数配置信息
     * @return bool 结果
     */
    function insertConfig(array $sysConfig): bool
    {
        $row = SysConfig::insertConfig($sysConfig);
        if($row > 0)
        {
            Redis::set(self::getCacheKey($sysConfig['configKey']), $sysConfig['configValue']);
        }
        return $row;
    }

    /**
     * 批量删除参数信息
     *
     * @param array $configIds 需要删除的参数ID
     * @return mixed 结果
     * @throws ParametersException
     */
    function deleteConfigByIds(array $configIds)
    {
        foreach ($configIds as $configId)
        {
            $config = self::selectConfigById($configId);
            if(UserConstants::YES == $config['configType'])
            {
                throw new ParametersException('内置参数' . $config['configKey'] . '不能删除');
            }
        }
        $count = SysConfig::deleteConfigByIds($configIds);
        if($count > 0)
        {
            $keys = Redis::keys(Constants::SYS_CONFIG_KEY . '*');
            Redis::delete($keys);
        }
        return $count;
    }

    /**
     * 修改参数配置
     *
     * @param int $configId 参数配置编号
     * @param array $sysConfig 参数配置信息
     * @return mixed 结果
     */
    function updateConfig(int $configId, array $sysConfig)
    {
        $row = SysConfig::updateConfig($configId, $sysConfig);
        if($row > 0)
        {
            Redis::set(self::getCacheKey($sysConfig['configKey']),$sysConfig['configValue']);
        }
        return $row;
    }

    /**
     * 清空缓存数据
     *
     * @return void
     */
    function clearCache()
    {
        $keys = Redis::keys(Constants::SYS_CONFIG_KEY . '*');
        Redis::delete($keys);
    }
}
