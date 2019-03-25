<?php

namespace app\site\service;
/**
 * 权限接口
 */
class PurviewService {
    /**
     * 获取模块权限
     */
    public function getSystemPurview() {
        return [
            'Config' => [
                'name' => '站点设置',
                'auth' => [
                    'index' => '站点信息',
                    'tpl' => '模板设置',
                ],
            ],
            
            'Search' => [
                'name' => '搜索管理',
                'auth' => [
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'del' => '删除',
                ],
            ],
            'Tpl' => [
                'name' => '模板管理',
                'auth' => [
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'del' => '删除',
                ],
            ],
        ];
    }


}
