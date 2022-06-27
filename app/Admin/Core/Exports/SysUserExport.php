<?php

namespace App\Admin\Core\Exports;

use App\Admin\Model\SysUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * 系统用户导出
 *
 * @author zjj
 */
class SysUserExport implements FromCollection, WithStyles
{

    private array $row;

    public function __construct(){
        $this->row = [
            'uid' => '用户序号',
            'username' => '登录名称',
            'nickname' => '用户名称',
            'email' => '用户邮箱',
            'phone' => '手机号码',
            'sex' => '用户性别',
            'status' => '帐号状态',
            'ip' => '最后登录IP',
            'createTime' => '最后登录时间',
            'deptName' => '部门名称',
            'leader' => '部门负责人',
        ];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        /*$row = $this->row;
        $data = $this->data;

        //设置表头
        foreach ($row[0] as $key => $value) {
            $key_arr[] = $key;
        }

        //输入数据
        foreach ($data as $key => &$value) {
            $js = [];
            for ($i=0; $i < count($key_arr); $i++) {
                $js = array_merge($js,[ $key_arr[$i] => $value[ $key_arr[$i] ] ]);
            }
            array_push($row, $js);
            unset($val);
        }
        return collect($row);*/
    }

    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
    }
}
