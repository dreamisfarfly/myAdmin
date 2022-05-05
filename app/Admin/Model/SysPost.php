<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 岗位表
 *
 * @author zjj
 */
class SysPost extends BaseModel
{

    protected $table = 'sys_post';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'post_id as postId',
        'post_code as postCode',
        'post_name as postName',
        'post_sort as postSort',
        'status',
        'create_by as createBy',
        'create_time as createTime',
        'remark'
    ];

    /**
     * 查询岗位数据集合
     *
     * @return LengthAwarePaginator|null
     */
    public static function selectPostList(): ?LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->select(self::SELECT_PARAMS)
        );
    }

}
