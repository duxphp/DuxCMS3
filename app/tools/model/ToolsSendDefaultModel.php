<?php

/**
 * 推送队列
 */
namespace app\tools\model;

use app\system\model\SystemModel;

class ToolsSendDefaultModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'default_id'
    ];


}