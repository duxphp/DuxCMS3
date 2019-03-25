<?php

/**
 * APP设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class AppConfigAdmin extends \app\wechat\admin\WechatAdmin {

    protected $_model = 'WechatApp';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => 'APP设置',
                'description' => '设置管理微信APP接口',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
            ],
        ];
    }

}