<?php

namespace App\Admin\Service\System;

/**
 * 参数配置 信息操作处理
 *
 * @author zjj
 */
interface ISysConfigService
{

    /**
     * 根据键名查询参数配置信息
     *
     * @param string $configKey 参数键名
     * @return mixed 参数键值
     */
    function selectConfigByKey(string $configKey);

    /**
     * 查询参数配置列表
     *
     * @param array $queryParam 参数配置信息
     * @return mixed 参数配置集合
     */
    function selectConfigList(array $queryParam);

    /**
     * 根据参数编号获取详细信息
     *
     * @param int $configId 参数配置ID
     * @return mixed 参数配置信息
     */
    function selectConfigById(int $configId);

    /**
     * 校验参数键名是否唯一
     *
     * @param string $configKey 参数配置key
     * @param ?int $configId 参数配置编号
     * @return mixed
     */
    function checkConfigKeyUnique(string $configKey, ?int $configId = null);

    /**
     * 新增参数配置
     *
     * @param array $sysConfig 参数配置信息
     * @return mixed 结果
     */
    function insertConfig(array $sysConfig);

    /**
     * 修改参数配置
     *
     * @param int $configId 参数配置编号
     * @param array $sysConfig 参数配置信息
     * @return mixed 结果
     */
    function updateConfig(int $configId, array $sysConfig);

    /**
     * 批量删除参数信息
     *
     * @param array $configIds 需要删除的参数ID
     * @return mixed 结果
     */
    function deleteConfigByIds(array $configIds);

    /**
     * 清空缓存数据
     *
     * @return mixed
     */
    function clearCache();

}
