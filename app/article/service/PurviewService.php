<?php

namespace app\article\service;
/**
 * 权限接口
 */
class PurviewService {
    /**
     * 获取模块权限
     */
    public function getSystemPurview() {
        return [
            'Content' => [
                'name' => '文章管理',
                'auth' => [
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'status' => '状态',
                    'del' => '删除',
                ]
            ],
            'Class' => [
                'name' => '文章分类',
                'auth' => [
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'status' => '状态',
                    'del' => '删除',
                ]
            ],
        ];
    }


}
