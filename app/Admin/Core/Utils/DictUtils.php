<?php

namespace App\Admin\Core\Utils;

use App\Admin\Core\Constant\Constants;
use Illuminate\Support\Facades\Redis;

/**
 * 字典工具类
 *
 * @author zjj
 */
class DictUtils
{

    /**
     * 分隔符
     */
    const SEPARATOR = ",";

    /**
     * 设置字典缓存
     *
     * @param string $key 参数键
     * @param array $dictData 字典数据列表
     */
    public static function setDictCache(string $key, array $dictData)
    {
        Redis::set(self::getCacheKey($key), $dictData);
    }

    public static function getDictCache(string $key)
    {
        return null;
    }

    /**
     * 清空字典缓存
     */
    public static function clearDictCache()
    {
        Redis::delete(Constants::SYS_DICT_KEY . "*");
    }

    /**
     * 设置cache key
     *
     * @param string $key 参数键
     * @return string 缓存键key
     */
    public static function getCacheKey(string $key)
    {
        return Constants::SYS_DICT_KEY . $key;
    }

}
