<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Utils\DictUtils;
use App\Admin\Model\SysDictData;
use App\Admin\Model\SysDictType;
use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Service\System\ISysDictTypeService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 字典 业务层
 *
 * @author zjj
 */
class SysDictTypeServiceImpl implements ISysDictTypeService
{

    /**
     * 根据条件分页查询字典类型
     *
     * @return LengthAwarePaginator
     */
    function selectDictTypeList(): LengthAwarePaginator
    {
        return SysDictType::selectDictTypeList();
    }

    /**
     * 根据字典类型ID查询信息
     *
     * @param int $dictId 字典类型ID
     * @return Builder|Model|object|null
     */
    function selectDictTypeById(int $dictId)
    {
        return SysDictType::selectDictTypeById($dictId);
    }

    /**
     * 校验字典类型称是否唯一
     *
     * @param int $dictId 字典类型
     * @param string $dictType 字典类型
     * @return mixed 结果
     */
    function checkDictTypeUnique(int $dictId, string $dictType): bool
    {
         $dictTypeData = SysDictType::checkDictTypeUnique($dictType);
         if($dictTypeData != null && $dictTypeData->dictId == $dictId)
         {
             return UserConstants::NOT_UNIQUE;
         }
         return UserConstants::UNIQUE;
    }

    /**
     * 新增保存字典类型信息
     *
     * @param array $data 字典类型信息
     * @return bool 结果
     */
    function insertDictType(array $data): bool
    {
        $row = SysDictType::insertDictType($data);
        if($row > 0)
        {
            DictUtils::clearDictCache();
        }
        return $row;
    }

    /**
     * 修改保存字典类型信息
     *
     * @param int $dictId 字典ID
     * @param array $data 字典类型信息
     * @return int 结果
     * @throws ParametersException
     */
    function updateDictType(int $dictId, array $data): int
    {
        try {
            DB::beginTransaction();
            $oldDict = SysDictType::selectDictTypeById($dictId);
            SysDictData::updateDictDataType($oldDict['dictType'], ['dictType'=>$data['dictType'],'updateBy'=>$data['updateBy']]);
            $row = SysDictType::updateDictType($dictId, $data);
            if( $row > 0)
            {
                DictUtils::clearDictCache();
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new ParametersException('操作失败');
        }
        return $row;
    }

    /**
     * 批量删除字典信息
     *
     * @param array $ids 需要删除的字典ID
     * @return mixed 结果
     * @throws ParametersException
     */
    function deleteDictTypeByIds(array $ids)
    {
        foreach ($ids as $id)
        {
            $dictType = self::selectDictTypeById($id);
            if(SysDictData::countDictDataByType($dictType['dictType']) > 0)
            {
                throw new ParametersException(sprintf('%s已分配,不能删除', $dictType['dictType']));
            }
        }
        $count = SysDictType::deleteDictTypeByIds($ids);
        if($count > 0){
            DictUtils::clearDictCache();
        }
        return $count;
    }

    /**
     * 根据所有字典类型
     *
     * @return Builder[]|Collection 字典类型集合信息
     */
    function selectDictTypeAll()
    {
        return SysDictType::selectDictTypeAll();
    }

    /**
     * 清空缓存数据
     *
     * @return void
     */
    function clearCache()
    {
        DictUtils::clearDictCache();
    }

    /**
     * 根据字典类型查询字典数据
     *
     * @param string $dictType 字典类型
     * @return Builder[]|Collection|null 字典数据集合信息
     */
    function selectDictDataByType(string $dictType)
    {
        $dictData = DictUtils::getDictCache($dictType);
        if($dictData != null)
        {
            return $dictData;
        }
        $dictData = SysDictData::selectDictDataByType($dictType);
        if($dictData != null)
        {
            DictUtils::setDictCache($dictType, $dictData);
            return $dictData;
        }
        return null;
    }
}
