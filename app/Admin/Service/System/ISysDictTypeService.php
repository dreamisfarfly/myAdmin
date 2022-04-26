<?php

namespace App\Admin\Service\System;

/**
 * 字典 业务层
 *
 * @author zjj
 */
interface ISysDictTypeService
{

    /**
     * 根据条件分页查询字典类型
     *
     * @return mixed
     */
    function selectDictTypeList();

    /**
     * 根据字典类型ID查询信息
     *
     * @param int $dictId 字典类型ID
     * @return mixed
     */
    function selectDictTypeById(int $dictId);

    /**
     * 校验字典类型称是否唯一
     *
     * @param int $dictId 字典类型
     * @param string $dictType 字典类型
     * @return mixed 结果
     */
    function checkDictTypeUnique(int $dictId, string $dictType);

    /**
     * 新增保存字典类型信息
     *
     * @param array $data 字典类型信息
     * @return mixed 结果
     */
    function insertDictType(array $data);

    /**
     * 修改保存字典类型信息
     *
     * @param int $dictId 字典ID
     * @param array $data 字典类型信息
     * @return mixed 结果
     */
    function updateDictType(int $dictId, array $data);

    /**
     * 批量删除字典信息
     *
     * @param array $ids 需要删除的字典ID
     * @return mixed 结果
     */
    function deleteDictTypeByIds(array $ids);

}
