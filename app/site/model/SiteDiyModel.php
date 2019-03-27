<?php

/**
 * 碎片管理
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SiteDiyModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'diy_id',
        'validate' => [
            'name' => [
                'len' => ['1, 20', '列表描述输入不正确!', 'must', 'all'],
            ],
        ],
    ];

}