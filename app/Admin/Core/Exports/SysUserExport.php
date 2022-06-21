<?php

namespace App\Admin\Core\Exports;

use App\Admin\Model\SysUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SysUserExport implements FromCollection
{

    /**
     * @return Collection
     */
    public function collection()
    {
        return SysUser::all();
    }

}
