<?php

/**
 * 发送模板管理
 */
namespace app\tools\model;

use app\system\model\SystemModel;

class ToolsSendTplModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'tpl_id',
        'validate' => [
            'title' => [
                'required' => ['', '模板标题不能为空!', 'must', 'all'],
            ],
            'content' => [
                'required' => ['', '模板内容不能为空!', 'must', 'all'],
            ],
        ],
        'format' => [
            'time' => [
                'function' => ['time', 'add'],
            ]
        ],
        'into' => '',
        'out' => '',
    ];

}