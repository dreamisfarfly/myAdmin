<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Security\Authentication;

/**
 * 数据字典信息
 *
 * @author zjj
 */
class SysDictDataController
{

    /**
     * 字典数据列表
     */
    public function list()
    {
        Authentication::hasPermit('system:dict:list');
    }

    /**
     * 查询字典数据详细
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:dict:query');
    }

    /**
     * 根据字典类型查询字典数据信息
     */
    public function dictType()
    {

    }

    /**
     * 新增字典类型
     */
    public function add()
    {
        Authentication::hasPermit('system:dict:add');
    }

    /**
     * 修改保存字典类型
     */
    public function edit()
    {
        Authentication::hasPermit('system:dict:edit');
    }

    /**
     * 修改保存字典类型
     */
    public function remove()
    {
        Authentication::hasPermit('system:dict:remove');
    }

}
