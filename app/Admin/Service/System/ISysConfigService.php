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

}
