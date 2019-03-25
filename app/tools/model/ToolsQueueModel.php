<?php

/**
 * 队列管理
 */
namespace app\tools\model;

use app\system\model\SystemModel;

class ToolsQueueModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'queue_id',
        'into' => '',
        'out' => '',
    ];

}