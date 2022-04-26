<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 字典表 数据层
 *
 * @author zjj
 */
class SysDictData extends BaseModel
{

    /**
     * 根据条件分页查询字典数据
     *
     * @return LengthAwarePaginator
     */
    public static function selectDictDataList(): LengthAwarePaginator
    {
        return self::query()->paginate();
    }

    /**
     * 根据字典数据ID查询信息
     *
     * @param int $dictCode
     * @return Builder|Model|object|null
     */
    public static function selectDictDataById(int $dictCode)
    {
        return self::query()->where('dict_code', $dictCode)->first();
    }

    /**
     * 同步修改字典类型
     *
     * @param string $oldDictType 旧字典类型
     * @param string $newDictType 新旧字典类型
     * @return int 结果
     */
    public static function updateDictDataType(string $oldDictType, string $newDictType): int
    {
        return self::query()
            ->where('dict_type', $oldDictType)
            ->update([
                'dict_type' => $newDictType
            ]);
    }

    /**
     * 查询字典数据
     *
     * @param string $dictType 字典类型
     * @return int 字典数据
     */
    public static function countDictDataByType(string $dictType)
    {
        return self::query()
            ->where('dict_type', $dictType)
            ->count();
    }

}
