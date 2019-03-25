<?php

/**
 * 小程序设置
 */
namespace app\wechat\model;

use app\system\model\SystemModel;

class WechatAppModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'id',
        'into' => '',
        'out' => '',
    ];

}