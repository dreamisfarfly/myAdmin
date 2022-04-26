<?php

namespace App\Admin\Service\System\Impl;

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
     * @return LengthAwarePaginator
     */
    function selectDictDataList(): LengthAwarePaginator
    {
        return SysDictData::selectDictDataList();
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

}
