<?php

/**
 * 小程序设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class MiniappConfigAdmin extends \app\wechat\admin\WechatAdmin {

    protected $_model = 'WechatMiniapp';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '小程序设置',
                'description' => '设置管理微信小程序接口',
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