<?php
namespace app\system\service;
/**
 * 系统菜单接口
 */
class MenuService {
    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return array(
            'index' => array(
                'name' => '首页',
                'icon' => 'home',
                'order' => 0,
                'url' => url('system/Index/index'),
            ),
            'system' => array(
                'name' => '设置',
                'icon' => 'cogs',
                'order' => 150,
                'menu' => array(
                    array(
                        'name' => '设置',
                        'order' => 10,
                        'menu' => array(
                            array(
                                'name' => '系统设置',
                                'url' => url('system/Config/index'),
                                'order' => 1
                            ),
                            array(
                                'name' => '上传驱动',
                                'url' => url('system/ConfigUpload/index'),
                                'order' => 2
                            ),
                            array(
                                'name' => 'API接口',
                                'url' => url('system/ConfigApi/index'),
                                'order' => 3
                            ),
                        )
                    ),
                    array(
                        'name' => '管理',
                        'icon' => 'users',
                        'order' => 20,
                        'menu' => array(
                            array(
                                'name' => '用户管理',
                                'url' => url('system/User/index'),
                                'order' => 1
                            ),
                            array(
                                'name' => '角色管理',
                                'url' => url('system/Role/index'),
                                'order' => 2
                            ),
                            array(
                                'name' => '前端日志',
                                'url' => url('system/Debug/index'),
                                'order' => 3
                            ),
                            array(
                                'name' => '后端日志',
                                'url' => url('system/SystemLog/index'),
                                'order' => 4
                            )
                        )
                    ),
                    array(
                        'name' => '应用',
                        'order' => 30,
                        'menu' => array(
                            array(
                                'name' => '应用管理',
                                'url' => url('system/Application/index'),
                                'order' => 1
                            ),
                        )
                    ),

                ),
            ),
        );
    }
}

