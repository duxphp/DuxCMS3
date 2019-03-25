<?php

/**
 * 标签工具
 */
namespace app\tools\model;

class ToolsLabelModel {


    public function getTips() {
        $list = hook('service', 'Tip', 'site');
        $data = array();
        foreach ((array)$list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        $data = array_sort($data, 'order', 'asc', true);
        //print_r($data);
        return $data;

    }


}