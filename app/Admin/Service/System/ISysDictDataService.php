<?php

namespace App\Admin\Service\System;

/**
 * 数据字典信息
 *
 * @author zjj
 */
interface ISysDictDataService
{

    /**
     * 根据条件分页查询字典数据
     *
     * @param array $queryParam 搜索参数
     * @return mixed
     */
    function selectDictDataList(array $queryParam);

    /**
     * 根据字典数据ID查询信息
     *
     * @param int $dictCode 字典数据ID
     * @return mixed
     */
    function selectDictDataById(int $dictCode);

    /**
     * 根据字典类型查询字典数据
     *
     * @param string $dictType 字典类型
     * @return mixed 字典数据集合信息
     */
    function selectDictDataByType(string $dictType);

    /**
     * 新增保存字典数据信息 结果
     *
     * @param array $sysDictData 字典数据信息
     * @return mixed
     */
    function insertDictData(array $sysDictData);

    /**
     * 修改保存字典数据信息
     *
     * @param int $dictCode 字典数据编号
     * @param array $dictData 字典数据信息
     * @return mixed 结果
     */
    function updateDictData(int $dictCode, array $dictData);

    /**
     * 批量删除字典数据信息
     *
     * @param array $ids 需要删除的字典数据ID
     * @return mixed 结果
     */
    function deleteDictDataByIds(array $ids);

    /**
     * 检测指定条件是否唯一
     *
     * @param array $sysDictData 字典数据信息
     * @param int|null $dictCode 字典数据编号
     * @return mixed 结果
     */
    function checkAssignUnique(array $sysDictData, ?int $dictCode = null);

}
