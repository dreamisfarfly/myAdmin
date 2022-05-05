<?php

namespace App\Admin\Core\Exception;

use App\Admin\Core\Constant\HttpStatus;

/**
 * 权限认证失败
 *
 * @author zjj
 */
class AuthorityCertificationException extends ApiException
{

    /**
     * @var int
     */
    protected int $coreErrorCode = HttpStatus::UNAUTHORIZED;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->coreMsg = '请求访问：'.request()->path().'，认证失败，无法访问系统资源';
    }

}
