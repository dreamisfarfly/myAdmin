<?php

namespace App\Admin\Core\Domain;

use App\Admin\Core\Constant\HttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * 表格分页数据对象
 *
 * @author zjj
 */
class TableDataInfo
{

    /** 总记录数 */
    protected int $total;

    /** 列表数据 */
    protected array $rows;

    /** 消息状态码 */
    protected int $code;

    /** 消息内容 */
    protected String $msg;

    /**
     * @param int $total
     * @param array $rows
     * @param int $code
     * @param string $msg
     */
    public function __construct(int $total, array $rows, int $code, string $msg)
    {
        $this->total = $total;
        $this->rows = $rows;
        $this->code = $code;
        $this->msg = $msg;
    }

    /**
     * 成功
     * @return JsonResponse
     */
    public function success(): JsonResponse
    {
        $jsonData = [
            'code' => $this->code,
            'msg' => $this->msg,
            'rows' => $this->rows,
            'total' => $this->total
        ];
        return Response::json($jsonData,HttpStatus::SUCCESS);
    }


}
