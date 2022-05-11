<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @param array $queryParam
     * @return LengthAwarePaginator|null
     */
    public static function selectPostList(array $queryParam): ?LengthAwarePaginator
    {
        return self::customPagination(
            self::query()
                ->when(isset($queryParam['status']),function($query)use($queryParam){
                    $query->where('status', $queryParam['status']);
                })
                ->when(isset($queryParam['postCode']),function($query)use($queryParam){
                    $query->where('post_code', 'like', $queryParam['postCode'].'%');
                })
                ->when(isset($queryParam['postName']),function($query)use($queryParam){
                    $query->where('post_name', 'like', $queryParam['postName'].'%');
                })
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

    /**
     * 查询职位信息
     * @param array $sysPost
     * @return Builder|Model|object|null
     */
    public static function selectPostByPost(array $sysPost)
    {
        return self::query()
            ->when(isset($sysPost['postId']), function ($query)use($sysPost){
                $query->where('post_id', $sysPost['postId']);
            })
            ->when(isset($sysPost['postName']), function ($query)use($sysPost){
                $query->where('post_name', $sysPost['postName']);
            })
            ->when(isset($sysPost['postCode']), function ($query)use($sysPost){
                $query->where('post_code', $sysPost['postCode']);
            })
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 新增岗位信息
     *
     * @param array $sysPost 岗位信息
     * @return bool 结果
     */
    public static function insertPost(array $sysPost): bool
    {
        $sysPost['createTime'] = date('Y-m-d H:i:s');
        return self::query()
            ->insert(self::uncamelize($sysPost));
    }

    /**
     * 修改保存岗位信息
     *
     * @param int $postId 岗位编号
     * @param array $sysPost 岗位信息
     * @return int 结果
     */
    public static function updatePost(int $postId, array $sysPost): int
    {
        $sysPost['updateTime'] = date('Y-m-d H:i:s');
        return self::query()->where('post_id', $postId)->update(self::uncamelize($sysPost));
    }

    /**
     * 批量删除岗位信息
     *
     * @param array $ids 需要删除的岗位ID
     * @return mixed 结果
     */
    public static function deletePostByIds(array $ids)
    {
        return self::query()->whereIn('post_id', $ids)->delete();
    }

}
