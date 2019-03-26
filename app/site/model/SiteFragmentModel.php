<?php

/**
 * 碎片管理
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SiteFragmentModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'fragment_id',
        'validate' => [
            'title' => [
                'len' => ['1, 20', '碎片描述输入不正确!', 'must', 'all'],
            ],
        ],
        'format' => [
            'title' => [
                'function' => ['html_in', 'all'],
            ],
            'content' => [
                'function' => ['html_in', 'all'],
            ],
        ],
        'into' => '',
        'out' => '',
    ];

    

}