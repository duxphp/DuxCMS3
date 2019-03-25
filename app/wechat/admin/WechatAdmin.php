<?php

/**
 * 微信设置公共模块
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class WechatAdmin extends \app\system\admin\SystemExtendAdmin {

    public $wechat = null;
    public $config = [];

    public function __construct() {
        parent::__construct();
        $target = target('wechat/Wechat', 'service');
        $target->init();
        $this->wechat = $target->wechat();
        $this->config = $target->config();
    }


}