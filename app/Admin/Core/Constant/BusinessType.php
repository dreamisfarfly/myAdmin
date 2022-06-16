<?php

namespace App\Admin\Core\Constant;

interface BusinessType
{

    /**
     * 其它
     */
    const OTHER = 0;

    /**
     * 新增
     */
    const INSERT = 1;

    /**
     * 修改
     */
    const UPDATE = 2;

    /**
     * 删除
     */
    const DELETE = 3;

    /**
     * 授权
     */
    const GRANT = 4;

    /**
     * 导出
     */
    const EXPORT = 5;

    /**
     * 导入
     */
    const IMPORT = 6;

    /**
     * 强退
     */
    const FORCE = 7;

    /**
     * 生成代码
     */
    const GENCODE = 8;

    /**
     * 清空数据
     */
    const CLEAN = 9;

}
