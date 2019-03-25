<?php

/**
 * 搜索管理
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SiteSearchModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'search_id',
        'format' => [
            'create_time' => [
                'function' => ['strtotime', 'all'],
            ],
            'update_time' => [
                'function' => ['strtotime', 'all'],
            ],
        ]
    ];

    public function loadList() {
        $list = parent::loadList();
        foreach ($list as $key => $vo) {
            $list[$key]['url'] = url($vo['app'] . '/Search/index', ['keyword' => $vo['keyword']]);
        }
        return $list;
    }

    public function stats($keyword, $app, $id = '') {
        $info = $this->getWhereInfo([
            'keyword' => $keyword
        ]);
        if($info) {
            $this->edit([
                'search_id' => $info['search_id'],
                'num' => $info['num']+1,
                'has_id' => $id,
                'update_time' => time()
            ]);
        }else {
            $this->add([
                'keyword' => $keyword,
                'app' => $app,
                'create_time' => time(),
                'update_time' => time(),
                'has_id' => $id
            ]);
        }
    }

    public function typeList() {
        $list = hook('service', 'Type', 'Search');
        $data = [];
        foreach ($list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        return $data;
    }

}