<?php

/**
 * 推送数据
 */
namespace app\tools\model;

use app\system\model\SystemModel;

class ToolsSendDataModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'data_id',
        'into' => '',
        'out' => '',
    ];


    /**
     * 获取数据接口
     * @return array
     */
    public function dataList() {
        $list = hook('service', 'Send', 'Data');
        $data = array();
        foreach ($list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        foreach ($data as $key => $vo) {
            foreach ($vo['type'] as $k => $v) {
                $data[$key]['type'][$k]['var'] = explode(',', $v['var']);
            }
        }
        return $data;
    }




}