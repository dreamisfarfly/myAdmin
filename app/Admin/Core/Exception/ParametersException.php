<?php

namespace App\Admin\Core\Exception;

use App\Admin\Core\Constant\CustomStatus;

/**
 * 参数错误
 *
 * @author zjj
 */
class ParametersException extends ApiException
{

    /**
     * @var int
     */
    protected int $coreErrorCode = CustomStatus::PARAMETER_ERROR;

    /**
     * @param string $msg
     */
    public function __construct(string $msg)
    {
        $this->coreMsg = $msg;
    }

}
