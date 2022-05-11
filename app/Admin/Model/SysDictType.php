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
class SysDictType extends BaseModel
{

    protected $table = 'sys_dict_type';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'dict_id as dictId',
        'dict_name as dictName',
        'dict_type as dictType',
        'status',
        'create_by as createBy',
        'create_time as createTime',
        'update_by as updateBy',
        'update_time as updateTime',
        'remark'
    ];

    /**
     * 根据条件分页查询字典类型
     *
     * @return LengthAwarePaginator
     */
    public static function selectDictTypeList(): LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
              ->select(self::SELECT_PARAMS)
        );
    }

    /**
     * 根据字典类型ID查询信息
     *
     * @param int $dictId 字典类型ID
     * @return Builder|Model|object|null
     */
    public static function selectDictTypeById(int $dictId)
    {
        return self::query()->where('dict_id', $dictId)->select(self::SELECT_PARAMS)->first();
    }

    /**
     * 校验字典类型称是否唯一
     *
     * @param string $dictType
     * @return Builder|Model|object|null
     */
    public static function checkDictTypeUnique(string $dictType)
    {
        return self::query()->where('dict_type', $dictType)->select(self::SELECT_PARAMS)->first();
    }

    /**
     * 新增保存字典类型信息
     *
     * @param array $data 字典类型信息
     * @return bool 结果
     */
    public static function insertDictType(array $data): bool
    {
        return self::query()->insert(self::parametersFilter($data));
    }

    /**
     * 修改保存字典类型信息
     *
     * @param int $dictId 字典ID
     * @param array $data 字典类型信息
     */
    public static function updateDictType(int $dictId, array $data): int
    {
        $data['updateTime'] = date('Y-m-d H:i:s');
        return self::query()
            ->where('dict_id', $dictId)
            ->update(self::uncamelize($data));
    }

    /**
     * 删除
     *
     * @param array $ids
     * @return mixed
     */
    public static function deleteDictTypeByIds(array $ids)
    {
        return self::query()
            ->whereIn('dict_id', $ids)
            ->delete();
    }

    /**
     * 根据所有字典类型
     *
     * @return Builder[]|Collection 字典类型集合信息
     */
    public static function selectDictTypeAll()
    {
        return self::query()->select(self::SELECT_PARAMS)->get();
    }

    /**
     * 过滤参数
     * @param array $data 过滤数据
     * @return array 过滤后数据
     */
    public static function parametersFilter(array $data): array
    {
        $fileData = [];
        if(isset($data['dictName']))
            $fileData['dict_name'] = $data['dictName'];
        if(isset($data['dictType']))
            $fileData['dict_type'] = $data['dictType'];
        if(isset($data['status']))
            $fileData['status'] = $data['status'];
        if(isset($data['remark']))
            $fileData['remark'] = $data['remark'];
        if(isset($data['createTime']))
            $fileData['create_time'] = $data['createTime'];
        if(isset($data['createBy']))
            $fileData['create_by'] = $data['createBy'];
        if(isset($data['updateBy']))
            $fileData['update_by'] = $data['updateBy'];
        if(isset($data['updateTime']))
            $fileData['update_time'] = $data['updateTime'];
        return $fileData;
    }


}
