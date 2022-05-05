<?php

namespace App\Admin\Core\Model;

use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Utils\PagingParametersUtil;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
