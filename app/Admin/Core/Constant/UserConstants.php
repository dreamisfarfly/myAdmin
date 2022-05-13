<?php

namespace App\Admin\Core\Constant;

/**
 * 用户常量信息
 *
 * @author zjj
 */
interface UserConstants
{

    /** 校验返回结果码 */
    const  UNIQUE = '0';

    const  NOT_UNIQUE = '1';

    /** 是否菜单外链（是） */
    const YES_FRAME = '0';

    /** 部门正常状态 */
    const DEPT_NORMAL = '0';

    /** 部门停用状态 */
    const DEPT_DISABLE = '1';

    /** 是否为系统默认（是） */
    const YES = 'Y';
}
