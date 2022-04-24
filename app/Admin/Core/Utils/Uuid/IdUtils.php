<?php

namespace App\Admin\Core\Utils\Uuid;

/**
 * ID生成器工具类
 *
 * @author zjj
 */
class IdUtils
{

    /**
     * 获取随机UUID
     *
     * @return string 随机UUID
     */
    public static function fastUUID(): string
    {
        $chars = md5(uniqid(mt_rand()), true);
        return substr( $chars, 0, 8 ) . '_'
            . substr( $chars, 8, 4) . '_'
            . substr( $chars, 12, 4) . '_'
            . substr( $chars, 16, 4) . '_'
            . substr( $chars, 20, 12);
    }

}
