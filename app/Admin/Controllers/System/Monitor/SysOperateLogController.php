<?php

namespace App\Admin\Controllers\System\Monitor;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Security\Authentication;
use App\Admin\Service\System\ISysConfigService;

/**
 * 操作日志记录
 *
 * @author zjj
 */
class SysOperateLogController extends BaseController
{

    /**
     * 操作日志列表
     */
    public function list()
    {
        Authentication::hasPermit('monitor:operlog:list');
    }

    /**
     * 操作日志删除
     */
    public function remove()
    {
        Authentication::hasPermit('monitor:operlog:remove');
    }

    /**
     * 操作日志全部清除
     */
    public function clean()
    {
        Authentication::hasPermit('monitor:operlog:remove');
    }

}
