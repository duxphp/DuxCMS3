<?php

namespace app\system\service;
/**
 * 权限接口
 */
class PurviewService {
    /**
     * 获取模块权限
     */
    public function getSystemPurview() {
        return array(
            'Index' => array(
                'name' => '系统状况',
                'auth' => array(
                    'index' => '系统首页',
                    'userData' => '个人资料',
                )
            ),
            'Notice' => array(
                'name' => '系统通知',
                'auth' => array(
                    'index' => '列表',
                    'del' => '删除',
                )
            ),
            'Update' => array(
                'name' => '系统更新',
                'auth' => array(
                    'index' => '更新信息',
                )
            ),
            'Config' => array(
                'name' => '系统设置',
                'auth' => array(
                    'index' => '站点信息',
                    'user' => '系统设置',
                    'info' => '系统信息',
                    'upload' => '上传设置',
                )
            ),
            'ConfigManage' => array(
                'name' => '配置管理',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'status' => '状态',
                    'del' => '删除',
                )
            ),
            'ConfigApi' => array(
                'name' => 'API接口',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'status' => '状态',
                    'del' => '删除',
                )
            ),
            'ConfigUpload' => array(
                'name' => '上传驱动',
                'auth' => array(
                    'index' => '列表',
                    'edit' => '编辑',
                )
            ),
            'User' => array(
                'name' => '用户管理',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'status' => '状态',
                    'del' => '删除',
                )
            ),
            'Role' => array(
                'name' => '角色管理',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'del' => '删除',
                )
            ),
            'Debug' => array(
                'name' => '前端日志',
                'auth' => array(
                    'index' => '列表',
                    'del' => '删除',
                )
            ),
            'SystemLog' => array(
                'name' => '后端日志',
                'auth' => array(
                    'index' => '列表',
                    'del' => '删除',
                )
            ),
            'Application' => array(
                'name' => '应用管理',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'del' => '删除',
                )
            ),
        );
    }


}
