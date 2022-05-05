<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * 字典表 数据层
 *
 * @author zjj
 */
class SysDictData extends BaseModel
{

    protected $table = 'sys_dict_data';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'dict_code as dictCode',
        'dict_sort as dictSort',
        'dict_label as dictLabel',
        'dict_value as dictValue',
        'dict_type as dictType',
        'css_class as cssClass',
        'list_class as listClass',
        'is_default as isDefault',
        'status',
        'create_by as createBy',
        'create_time as createTime',
        'update_by as updateBy',
        'update_time as updateTime'
    ];

    /**
     * 根据条件分页查询字典数据
     *
     * @return LengthAwarePaginator
     */
    public static function selectDictDataList(): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->select(self::SELECT_PARAMS)
        );
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
    public static function countDictDataByType(string $dictType): int
    {
        return self::query()
            ->where('dict_type', $dictType)
            ->count();
    }


    /**
     * 根据字典类型查询字典数据
     *
     * @param string $dictType 字典类型
     * @return Builder[]|Collection 字典数据集合信息
     */
    public static function selectDictDataByType(string $dictType)
    {
        return self::query()
            ->where('status', 0)
            ->where('dict_type', $dictType)
            ->select(self::SELECT_PARAMS)
            ->orderBy('dict_sort')
            ->get();
    }

}
