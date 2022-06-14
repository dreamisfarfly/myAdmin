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
     * @param array $queryParam
     * @return mixed
     */
    function selectDictTypeList(array $queryParam);

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
     * @param string $dictType 字典类型
     * @param ?int $dictId 字典编号
     * @return mixed 结果
     */
    function checkDictTypeUnique(string $dictType, ?int $dictId = null);

    /**
     * 校验字典类型称是否存在
     *
     * @param string $dictType 字典类型
     * @return mixed
     */
    function checkDictTypeExist(string $dictType);

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

    /**
     * 根据所有字典类型
     *
     * @return mixed 字典类型集合信息
     */
    function selectDictTypeAll();

    /**
     * 清空缓存数据
     *
     * @return mixed
     */
    function clearCache();

    /**
     * 根据字典类型查询字典数据
     *
     * @param string $dictType 字典类型
     * @return mixed 字典数据集合信息
     */
    function selectDictDataByType(string $dictType);

}
