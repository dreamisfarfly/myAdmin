<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyIds;

/**
 * ids
 *
 * @author zjj
 */
class Ids extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'ids' => new VerifyIds()
        ];
    }

}
