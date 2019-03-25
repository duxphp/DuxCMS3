<?php

/**
 * 队列管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;

class QueueAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'toolsQueue';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '队列管理',
                'description' => '管理系统任务队列',
            ],
            'fun' => [
                'index' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'remark'
        ];
    }

}