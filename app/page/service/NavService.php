<?php
namespace app\page\service;
/**
 * 站点导航接口
 */
class NavService {
    /**
     * 获取导航结构
     */
    public function getSiteNav() {

        $list = target('page/Page')->loadTreeList();
        return array(
            'page' => array(
                'name' => '页面',
                'target' => 'page/Page',
                'list' => $list,
            ),
        );
    }
}

