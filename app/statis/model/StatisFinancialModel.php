<?php

/**
 * 财务统计
 */

namespace app\statis\model;

use app\system\model\SystemModel;

class StatisFinancialModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'financial_id'
    ];

    /**
     * 财务类型接口
     * @param string $label
     * @return array|mixed
     */
    public function typeList($label = '') {
        $list = hook('service', 'Type', 'Financial');
        $data = [];
        foreach ($list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }

        $dataArray = [];
        $i = 0;
        foreach ($data as $key => $vo) {
            $i++;
            $topId = $i;
            $dataArray[$key] = [
                'name' => $vo['name'],
                'full_name' => $vo['name'],
                'key' => $key,
                'full_key' => $key,
                'cid' => $topId,
                'pid' => 0
            ];
            foreach ($vo['list'] as $k => $v) {
                $i++;
                $parentId = $i;
                $dataArray[$k] = [
                    'name' => $v['name'],
                    'full_name' => $vo['name'] . $v['name'],
                    'key' => $k,
                    'full_key' => $k,
                    'cid' => $parentId,
                    'pid' => $topId,
                ];
                if(empty($v['list'])) {
                    continue;
                }
                foreach ($v['list'] as $subKey => $subList) {
                    $i++;
                    $subId = $i;
                    $dataArray[$k . '_' . $subKey] = [
                        'name' => $subList['name'],
                        'full_name' => $vo['name'] . $v['name'] . ' - ' . $subList['name'],
                        'key' => $k,
                        'sub_key' => $subKey,
                        'full_key' => $k . '_' . $subKey,
                        'cid' => $subId,
                        'pid' => $parentId,
                    ];
                }
            }
        }
        $parentId = 0;
        if($label) {
            $info = $dataArray[$label];
            $parentId = $info['cid'];
        }
        $class = new \dux\lib\Category(['cid', 'pid', 'name', 'cname']);
        $list = $class->getTree($dataArray, $parentId);

        $newData = [];
        foreach ($list as $vo) {
            $newData[$vo['full_key']] = $vo;
        }
        return $newData;
    }
}
