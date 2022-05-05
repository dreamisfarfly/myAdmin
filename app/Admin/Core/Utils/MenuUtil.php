<?php

namespace App\Admin\Core\Utils;

/**
 * 菜单工具类
 *
 * @author zjj
 */
class MenuUtil
{

    /**
     * 菜单树形结构
     * @param array $data
     * @param int $pid
     * @return array
     */
    public static function menuTree(array $data, int $pid = 0): array
    {
        $result = array();
        foreach ($data as $v){
            if($v['parentId'] == $pid){
                $temp = [];
                $temp['name'] = $v['path'];
                $v['visible'] == 0?$temp['hidden'] = false:$temp['hidden']  = true;
                $v['isCache'] == 0?$isCache = true:$isCache = false;
                $temp['meta'] = [
                    'icon' => $v['icon'],
                    'noCache' => $isCache,
                    'title' => $v['menuName']
                ];
                if($v['menuType'] == 'M')
                {
                    $temp['path'] = '/'.$v['path'];
                    $v['isFrame'] == 0?$temp['redirect']='':$temp['redirect'] = 'noRedirect';
                    $temp['alwaysShow'] = true;
                    if($v['parentId'] == 0){
                        $temp['component'] = 'Layout';
                    }else{
                        $temp['component'] = 'ParentView';
                    }
                    $temp['children'] = self::menuTree($data,$v['menuId']);
                }
                if($v['menuType'] == 'C')
                {
                    $temp['path'] = $v['path'];
                    $temp['component'] = $v['component'];
                }
                $result[] = $temp;
            }
        }
        return $result;
    }

    /**
     * 选项树形结构
     * @param array $data
     * @param int $pid
     * @return array
     */
    public static function optionMenuTree(array $data, int $pid = 0): array
    {
        $result = array();
        foreach($data as $v){
            $temp = array();
            if($v['parentId'] == $pid){
                $temp['id'] = $v['menuId'];
                $temp['label'] = $v['menuName'];
                $temp['children'] = self::optionMenuTree($data,$v['menuId']);
                $result[] = $temp;
            }
        }
        return $result;
    }

}
