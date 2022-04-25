<?php

namespace App\Admin\Controllers\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 字典表 数据层
 *
 * @author zjj
 */
class SysDictType extends BaseModel
{

    protected $table = 'sys_dict_type';

    /**
     * 根据条件分页查询字典类型
     *
     * @return LengthAwarePaginator
     */
    public static function selectDictTypeList(): LengthAwarePaginator
    {
        return self::query()->paginate();
    }

    /**
     * 根据字典类型ID查询信息
     *
     * @param int $dictId 字典类型ID
     * @return Builder|Model|object|null
     */
    public static function selectDictTypeById(int $dictId)
    {
        return self::query()->where('dict_id', $dictId)->first();
    }

    /**
     * 校验字典类型称是否唯一
     *
     * @param string $dictType
     * @return Builder|Model|object|null
     */
    public static function checkDictTypeUnique(string $dictType)
    {
        return self::query()->where('dict_type', $dictType)->first();
    }

    /**
     * 新增保存字典类型信息
     *
     * @param array $data 字典类型信息
     * @return bool 结果
     */
    public static function insertDictType(array $data): bool
    {
        return self::query()->insert($data);
    }

}
