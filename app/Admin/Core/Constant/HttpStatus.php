<?php

namespace App\Admin\Core\Constant;

/**
 * HTTP状态码
 */
interface HttpStatus
{

    /**
     * 操作成功
     */
    const SUCCESS = 200;

    /**
     * 未授权
     */
    const UNAUTHORIZED = 401;

    /**
     * 访问受限，授权过期
     */
    const FORBIDDEN = 403;

    /**
     * 资源，服务未找到
     */
    const NOT_FOUND = 404;

    /**
     * 系统内部错误
     */
    const  ERROR = 500;

}
