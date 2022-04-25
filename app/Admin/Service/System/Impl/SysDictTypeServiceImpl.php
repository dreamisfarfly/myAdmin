<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Controllers\Model\SysDictType;
use App\Admin\Core\Constant\UserConstants;
use App\Admin\Service\System\ISysDictTypeService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
         if($dictType != null && $dictTypeData->dictId == $dictId)
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
        return SysDictType::insertDictType($data);
    }
}
