<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * 查询所有岗位
     *
     * @return Builder[]|Collection 岗位列表
     */
    public static function selectPostAll()
    {
        return self::query()
            ->select(self::SELECT_PARAMS)
            ->get();
    }

    /**
     * 根据用户ID获取岗位选择框列表
     *
     * @param int $userId 用户ID
     * @return \Illuminate\Support\Collection 选中岗位ID列表
     */
    public static function selectPostListByUserId(int $userId): \Illuminate\Support\Collection
    {
        return self::query()
            ->from('sys_post as p')
            ->leftJoin('sys_user_post as up',function($query){
                $query->on('up.post_id', '=', 'p.post_id');
            })
            ->leftJoin('sys_user as u', function($query){
                $query->on('u.user_id', '=', 'up.user_id');
            })
            ->where('u.user_id',$userId)
            ->pluck('p.post_id');
    }



}
