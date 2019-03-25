<?php

namespace app\statis\service;
/**
 * 菜单接口
 */
class MenuService {
    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return [
            'statis' => [
                'name' => '统计',
                'icon' => 'bar-chart',
                'order' => 20,
                'menu' => [
                    [
                        'name' => '商城',
                        'order' => 0,
                        'menu' => [
                            [
                                'name' => '访问统计',
                                'url' => url('statis/SiteViews/index'),
                                'order' => 0,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

