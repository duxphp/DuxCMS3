<?php
namespace app\tools\service;
/**
 * 权限接口
 */
class PurviewService {
    /**
     * 获取模块权限
     */
    public function getSystemPurview() {
        return array(
            'SendData' => array(
                'name' => '推送设置',
                'auth' => array(
                    'index' => '设置',
                )
            ),
            'Send' => array(
                'name' => '推送管理',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'info' => '详情',
                )
            ),
            'SendConf' => array(
                'name' => '参数设置',
                'auth' => array(
                    'index' => '列表',
                    'setting' => '配置',
                )
            ),
            'SendTpl' => array(
                'name' => '推送模板',
                'auth' => array(
                    'index' => '列表',
                    'add' => '添加',
                    'edit' => '编辑',
                    'del' => '删除',
                )
            ),
            'SendDefault' => array(
                'name' => '默认设置',
                'auth' => array(
                    'index' => '设置',
                )
            ),
            'Label' => array(
                'name' => '标签生成器',
                'auth' => array(
                    'index' => '生成工具',
                )
            ),
            'Queue' => array(
                'name' => '队列管理',
                'auth' => array(
                    'index' => '列表',
                )
            ),
            'QueueConf' => array(
                'name' => '队列设置',
                'auth' => array(
                    'index' => '设置',
                )
            ),
            'Api' => array(
                'name' => 'Api文档',
                'auth' => array(
                    'index' => '管理',
                    'make' => '生成',
                )
            ),
        );
    }


}
