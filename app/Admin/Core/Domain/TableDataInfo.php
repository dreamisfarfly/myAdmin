<?php

namespace App\Admin\Core\Domain;

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

}
