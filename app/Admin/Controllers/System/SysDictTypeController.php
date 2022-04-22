<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Security\Authentication;

/**
 * 数据字典信息
 *
 * @author zjj
 */
class SysDictTypeController
{

    /**
     * 列表
     */
    public function list()
    {
        Authentication::hasPermit('system:dict:list');
    }

    /**
     * 查询字典类型详细
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:dict:query');
    }

    /**
     * 新增字典类型
     */
    public function add()
    {
        Authentication::hasPermit('system:dict:add');
    }

    /**
     * 修改字典类型
     */
    public function edit()
    {
        Authentication::hasPermit('system:dict:edit');
    }

    /**
     * 删除字典类型
     */
    public function remove()
    {
        Authentication::hasPermit('system:dict:remove');
    }

    /**
     * 清空缓存
     */
    public function clearCache()
    {
        Authentication::hasPermit('system:dict:remove');
    }

    /**
     * 获取字典选择框列表
     */
    public function optionSelect()
    {

    }

}
