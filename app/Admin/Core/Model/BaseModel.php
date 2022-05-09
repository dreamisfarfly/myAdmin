<?php

namespace App\Admin\Core\Model;

use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Utils\PagingParametersUtil;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * 基类模型
 *
 * @author
 */
class BaseModel extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 自定义分页
     *
     * @param Builder $builder
     * @return ?LengthAwarePaginator
     */
    public static function customPagination(Builder $builder): ?LengthAwarePaginator
    {
        $pageNum = PagingParametersUtil::getPagingParam('pageNum');
        $pageSize = PagingParametersUtil::getPagingParam('pageSize',10);
        return $builder->paginate($pageSize,['*'],'pageNum',$pageNum);
    }

    /**
     * 驼峰命名转下划线命名
     *
     * @param array $camelCapsArray
     * @param array|null $removeKey
     * @param string $separator
     * @return array
     */
    public static function uncamelize(array $camelCapsArray, ?array $removeKey = null, string $separator='_')
    {
        $keyArray = array_keys($camelCapsArray);
        $temp = [];
        foreach ($keyArray as $item)
        {
            if(!($removeKey != null && in_array($item,$removeKey)))
            {
                $key = strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $item));
                Log::info($key);
                $temp[$key] = $camelCapsArray[$item];
            }
        }
        return $temp;
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

}
