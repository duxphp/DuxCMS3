<?php

/**
 * 调试信息
 */
namespace app\system\model;

use app\system\model\SystemModel;

class SystemDebugModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'debug_id',
    ];

    public function loadList($where = [], $limit = 0, $order = '') {
        return parent::loadList($where, $limit, 'debug_id desc');
    }
}