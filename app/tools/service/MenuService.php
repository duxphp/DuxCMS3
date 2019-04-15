<?php

namespace app\tools\service;
/**
 * 菜单接口
 */
class MenuService {

    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return array(
            'tools' => array(
                'name' => '工具',
                'icon' => 'cubes',
                'order' => 100,
                'menu' => array(
                    array(
                        'name' => '推送',
                        'order' => 0,
                        'menu' => array(
                            array(
                                'name' => '推送管理',
                                'url' => url('tools/Send/index'),
                                'order' => 0
                            ),
                            array(
                                'name' => '模板管理',
                                'url' => url('tools/SendTpl/index'),
                                'order' => 1
                            ),
                            array(
                                'name' => '参数设置',
                                'url' => url('tools/SendConf/index'),
                                'order' => 2
                            ),
                            array(
                                'name' => '默认设置',
                                'url' => url('tools/SendDefault/index'),
                                'order' => 2
                            ),
                        )
                    ),
                    array(
                        'name' => '队列',
                        'order' => 2,
                        'menu' => array(
                            array(
                                'name' => '队列管理',
                                'url' => url('tools/Queue/index'),
                                'order' => 0
                            ),
                            array(
                                'name' => '队列设置',
                                'url' => url('tools/QueueConf/index'),
                                'order' => 1
                            ),
                        )
                    ),
                    array(
                        'name' => '其他',
                        'order' => 3,
                        'menu' => array(
                            array(
                                'name' => 'Api文档',
                                'url' => url('tools/Api/index'),
                                'order' => 0
                            ),
                        )
                    ),
                ),
            ),
        );
    }
}

