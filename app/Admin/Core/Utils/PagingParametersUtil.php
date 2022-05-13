<?php

namespace App\Admin\Core\Utils;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * 分页参数获取工具类
 */
class PagingParametersUtil
{

    /**
     * 分页参数获取
     * @param string $key
     * @param int $default
     * @return int|mixed
     */
    public static function getPagingParam(string $key, int $default = 1)
    {
        try {
            if(request()->exists($key) && preg_match("/^[1-9][0-9]*$/" ,request()->get($key)))
                return request()->get($key);
            else
                return $default;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return $default;
        }
    }

    /**
     * 排序参数
     * @return string[]|null
     */
    public static function getPagingSortParam(): ?array
    {
        try {
            if(request()->exists('orderByColumn') && request()->exists('isAsc'))
            {
                if(request()->get('isAsc') == 'ascending')
                {
                    return [
                        'column' => request()->get('orderByColumn'),
                        'sort' => 'asc'
                    ];
                }
                if(request()->get('isAsc') == 'descending')
                {
                    return [
                        'column' => request()->get('orderByColumn'),
                        'sort' => 'desc'
                    ];
                }
            }
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return null;
        }
        return null;
    }

}
