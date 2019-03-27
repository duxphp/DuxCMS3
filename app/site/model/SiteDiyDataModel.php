<?php

/**
 * 碎片管理
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SiteDiyDataModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'data_id',
        'validate' => [
            'title' => [
                'len' => ['1, 20', '标题输入不正确!', 'must', 'all'],
            ],
        ],
    ];

}