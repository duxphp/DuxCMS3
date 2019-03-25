<?php

/**
 * 推荐位管理
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SitePositionModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'pos_id',
        'validate' => [
            'name' => [
                'len' => ['1, 20', '推荐位名称输入不正确!', 'must', 'all'],
            ],
        ],
        'format' => [
            'name' => [
                'function' => ['htmlspecialchars', 'all'],
            ],
        ],
        'into' => '',
        'out' => '',
    ];

    public function loadList($where = [], $limit = 0, $order = '') {
        return parent::loadList($where, $limit, 'sort asc, pos_id asc');
    }

}