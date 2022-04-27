<?php

namespace App\Admin\Core\Model;

use App\Admin\Core\Exception\ParametersException;
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
        $pageNum = 1;
        $pageSize = 10;
        try {
            if(request()->exists('pageNum') && preg_match("/^[1-9][0-9]*$/" ,request()->get('pageNum')))
                $pageNum = request()->get('pageNum');
            if(request()->exists('pageSize') && preg_match("/^[1-9][0-9]*$/" ,request()->get('pageSize')))
                $pageSize = request()->get('pageSize');
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return null;
        }
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
