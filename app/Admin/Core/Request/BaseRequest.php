<?php

namespace App\Admin\Core\Request;

use App\Admin\Core\Exception\ParametersException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * 请求自定义基类
 *
 * @author zjj
 */
class BaseRequest extends FormRequest
{

    /**
     * @param Validator $validator
     * @throws ParametersException
     */
    public function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        throw new ParametersException($error);
    }

    /**
     * 获取请求参数
     * @param array $key
     * @return array
     */
    public function getParamsData(array $key): array
    {
        return request()->only($key);
    }

}
