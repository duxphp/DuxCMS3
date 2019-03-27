<?php

namespace app\site\service;
/**
 * 系统菜单接口
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
                        'name' => '站点',
                        'order' => 0,
                        'menu' => [
                            [
                                'name' => '站点设置',
                                'url' => url('site/Config/index'),
                                'order' => 0,
                            ],
                            [
                                'name' => '搜索管理',
                                'url' => url('site/Search/index'),
                                'order' => 1,
                            ],
                            [
                                'name' => '碎片管理',
                                'url' => url('site/Fragment/index'),
                                'order' => 2,
                            ],
                        ],
                    ],
                    [
                        'name' => '自定义列表',
                        'order' => 1,
                        'menu' => [
                            [
                                'name' => '列表管理',
                                'url' => url('site/Diy/index'),
                                'order' => 0,
                            ],
                            [
                                'name' => '内容管理',
                                'url' => url('site/DiyData/index'),
                                'order' => 1,
                            ],
                        ],
                    ]
                ],
            ],
        ];
    }
}

