<?php

namespace app\wechat\service;
/**
 * 菜单接口
 */
class MenuService {
    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return [
            'system' => [
                'menu' => [
                    [
                        'name' => '微信设置',
                        'order' => 10,
                        'menu' => [
                            [
                                'name' => '公众号设置',
                                'url' => url('wechat/WechatConfig/index'),
                                'order' => 0,
                            ],
                            [
                                'name' => '小程序设置',
                                'url' => url('wechat/MiniappConfig/index'),
                                'order' => 1,
                            ],
                            [
                                'name' => 'APP设置',
                                'url' => url('wechat/AppConfig/index'),
                                'order' => 2,
                            ],
                            [
                                'name' => '自定义菜单',
                                'url' => url('wechat/MenuConfig/index'),
                                'order' => 3,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

