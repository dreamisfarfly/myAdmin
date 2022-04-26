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
     * @return mixed
     */
    function selectDictDataList();

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

}
