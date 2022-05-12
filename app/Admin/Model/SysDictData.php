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
        'update_time as updateTime',
        'remark'
    ];

    /**
     * 根据条件分页查询字典数据
     *
     * @param array $queryParam
     * @return LengthAwarePaginator
     */
    public static function selectDictDataList(array $queryParam): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->when(isset($queryParam['status']),function($query)use($queryParam){
                    $query->where('status', $queryParam['status']);
                })
                ->when(isset($queryParam['dictType']),function($query)use($queryParam){
                    $query->where('dict_type', $queryParam['dictType']);
                })
                ->when(isset($queryParam['dictLabel']),function($query)use($queryParam){
                    $query->where('dict_label', $queryParam['dictLabel']);
                })
                ->select(self::SELECT_PARAMS)
                ->orderBy('dict_sort')
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
        return self::query()->where('dict_code', $dictCode)->select(self::SELECT_PARAMS)->first();
    }

    /**
     * 同步修改字典类型
     *
     * @param string $oldDictType 旧字典类型
     * @param array $newDict
     * @return int 结果
     */
    public static function updateDictDataType(string $oldDictType, array $newDict): int
    {
        $newDict['updateTime'] = date('Y-m-d H:i:s');
        return self::query()
            ->where('dict_type', $oldDictType)
            ->update(self::uncamelize($newDict));
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

    /**
     * 新增字典数据信息
     *
     * @param array $sysDictData 字典数据信息
     * @return bool 结果
     */
    public static function insertDictData(array $sysDictData): bool
    {
        $sysDictData['createTime'] = date('Y-m-d H:i:s');
        return self::query()->insert(self::uncamelize($sysDictData));
    }

    /**
     * 修改保存字典数据信息
     *
     * @param int $dictCode 字典数据编号
     * @param array $dictData 字典数据信息
     * @return int 结果
     */
    public static function updateDictData(int $dictCode, array $dictData): int
    {
        $dictData['updateTime'] = date('Y-m-d H:i:s');
        return self::query()
            ->where('dict_code', $dictCode)
            ->update(self::uncamelize($dictData));
    }

    /**
     * 批量删除字典数据信息
     *
     * @param array $dictCodes 需要删除的字典数据ID
     * @return mixed 结果
     */
    public static function deleteDictDataByIds(array $dictCodes)
    {
        return self::query()->whereIn('dict_code', $dictCodes)->delete();
    }

    /**
     * 查询字典数据记录
     * @param array $dictData
     * @return Builder|Model|mixed|object|null
     */
    public static function selectDictDataByDictData(array $dictData)
    {
        return self::query()
            ->when(isset($dictData['dictLabel']),function($query)use($dictData){
                $query->where('dict_label', $dictData['dictLabel']);
            })
            ->when(isset($dictData['dictValue']),function($query)use($dictData){
                $query->where('dict_value', $dictData['dictValue']);
            })
            ->when(isset($dictData['dictType']),function($query)use($dictData){
                $query->where('dict_type', $dictData['dictType']);
            })
            ->select(self::SELECT_PARAMS)
            ->first();
    }

}
