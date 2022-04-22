<?php

namespace App\Admin\Controllers\System\Monitor;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Security\Authentication;

/**
 * 在线用户监控
 *
 * @author zjj
 */
class SysUserOnlineController extends BaseController
{

    /**
     * 在线用户列表
     */
    public function list()
    {
        Authentication::hasPermit('monitor:online:list');
    }

    /**
     * 强退用户
     */
    public function forceLogout()
    {
        Authentication::hasPermit('monitor:online:forceLogout');
    }

}
