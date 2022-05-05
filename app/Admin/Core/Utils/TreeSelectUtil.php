<?php

namespace App\Admin\Core\Utils;

/**
 * Treeselect树结构
 *
 * @author zjj
 */
class TreeSelectUtil
{

    /**
     * 格式化输出
     *
     * @param array $data
     * @param int $prentId
     * @param string $id
     * @param string $label
     * @param string $parentId
     * @return array
     */
    public static function collect(array $data,int $prentId = 0, string $id = 'id', string $label ='label', string $parentId = 'parentId'): array
    {
        $item = [];
        foreach ($data as $key => $items)
        {
            if($items[$parentId] == $prentId){
                $temp = [
                  'id' => $items[$id],
                  'label' => $items[$label]
                ];
                unset($data[$key]);
                $temp['children'] = self::collect($data, $items[$id], $id, $label);
                if(count($temp['children']) == 0){
                    unset($temp['children']);
                }
                array_push($item,$temp);
            }
        }
        return $item;
    }

}
