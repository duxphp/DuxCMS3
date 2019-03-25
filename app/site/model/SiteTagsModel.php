<?php

/**
 * TAGS管理
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SiteTagsModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'tag_id',
        'validate' => [
            'name' => [
                'len' => ['1, 50', 'TAG输入不正确!', 'must', 'all'],
            ],
        ],
        'format' => [
            'name' => [
                'function' => ['htmlspecialchars', 'all'],
            ],
            'quote' => [
                'function' => ['intval', 'all'],
            ],
            'view' => [
                'function' => ['intval', 'all'],
            ],
        ],
        'into' => '',
        'out' => '',
    ];

    public function contentTags($app, $tagsId) {
        $list = $this->loadList(['_sql' => 'tag_id IN (' . $tagsId . ')', 'app' => $app]);
        if (empty($list)) {
            return [];
        }
        foreach ($list as $key => $vo) {
            $list[$key]['url'] = url($vo['app'] . '/Tags/index', ['name' => $vo['name']]);
        }
        return $list;
    }

    public function loadList($where = [], $limit = 10, $order = 'tag_id desc') {
        $list = parent::loadList($where, $limit, $order);

        if($list) {
            foreach ($list as $key => $vo) {
                $list[$key]['url'] = url($vo['app'] . '/Tags/index', ['name' => $vo['name']]);
            }
        }
        return $list;
    }

}