<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Utils\DictUtils;
use App\Admin\Model\SysDictData;
use App\Admin\Service\System\ISysDictDataService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * 字典 业务层处理
 *
 * @author zjj
 */
class SysDictDataServiceImpl implements ISysDictDataService
{

    /**
     * 根据条件分页查询字典数据
     *
     * @param array $queryParam 搜索参数
     * @return LengthAwarePaginator
     */
    function selectDictDataList(array $queryParam): LengthAwarePaginator
    {
        return SysDictData::selectDictDataList($queryParam);
    }

    /**
     * 根据字典数据ID查询信息
     *
     * @param int $dictCode 字典数据ID
     * @return Builder|Model|object|null
     */
    function selectDictDataById(int $dictCode)
    {
        return SysDictData::selectDictDataById($dictCode);
    }

    /**
     * 根据字典类型查询字典数据
     *
     * @param string $dictType 字典类型
     * @return Builder[]|Collection|null 字典数据集合信息
     */
    function selectDictDataByType(string $dictType)
    {
        $dictData = SysDictData::selectDictDataByType($dictType);
        if($dictData->isNotEmpty())
        {
            return $dictData;
        }
        return null;
    }

    /**
     * 新增保存字典数据信息 结果
     *
     * @param array $sysDictData 字典数据信息
     * @return bool
     */
    function insertDictData(array $sysDictData): bool
    {
        $row = SysDictData::insertDictData($sysDictData);
        if($row > 0)
        {
            DictUtils::clearDictCache();
        }
        return $row;
    }

    /**
     * 修改保存字典数据信息
     *
     * @param int $dictCode 字典数据编号
     * @param array $dictData 字典数据信息
     * @return int 结果
     */
    function updateDictData(int $dictCode, array $dictData): int
    {
        $row = SysDictData::updateDictData($dictCode, $dictData);
        if($row > 0)
        {
            DictUtils::clearDictCache();
        }
        return $row;
    }

    /**
     * 批量删除字典数据信息
     *
     * @param array $ids 需要删除的字典数据ID
     * @return mixed 结果
     */
    function deleteDictDataByIds(array $ids)
    {
        $row = SysDictData::deleteDictDataByIds($ids);
        if($row > 0)
        {
            DictUtils::clearDictCache();
        }
        return $row;
    }

    /**
     * 检测指定条件是否唯一
     *
     * @param array $sysDictData 字典数据信息
     * @param int|null $dictCode 字典数据编号
     * @return string 结果
     */
    function checkAssignUnique(array $sysDictData, ?int $dictCode = null): string
    {
        $info = SysDictData::selectDictDataByDictData($sysDictData);
        if($info != null && $info['dictCode'] != $dictCode)
        {
            return UserConstants::NOT_UNIQUE;
        }
        return UserConstants::UNIQUE;
    }
}
