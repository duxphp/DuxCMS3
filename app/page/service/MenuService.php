<?php
namespace app\page\service;
/**
 * 系统菜单接口
 */
class MenuService {
    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return array(
            'page' => array(
                'name' => '页面',
                'icon' => 'file-word-o',
                'order' => 3,
                'menu' => array(
                    array(
                        'name' => '页面',
                        'order' => 1,
                        'menu' => array(
                            array(
                                'name' => '页面管理',
                                'icon' => 'bars',
                                'url' => url('page/Page/index'),
                                'order' => 0,
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}
