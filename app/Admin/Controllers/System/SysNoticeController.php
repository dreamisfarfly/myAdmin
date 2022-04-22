<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Security\Authentication;

/**
 * 公告 信息操作处理
 *
 * @author zjj
 */
class SysNoticeController extends BaseController
{

    /**
     * 获取通知公告列表
     */
    public function list()
    {
        Authentication::hasPermit('system:notice:list');
    }

    /**
     * 根据通知公告编号获取详细信息
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:notice:query');
    }

    /**
     * 新增通知公告
     */
    public function add()
    {
        Authentication::hasPermit('system:notice:add');
    }

    /**
     * 修改通知公告
     */
    public function edit()
    {
        Authentication::hasPermit('system:notice:edit');
    }

    /**
     * 删除通知公告
     */
    public function remove()
    {
        Authentication::hasPermit('system:notice:remove');
    }
}
