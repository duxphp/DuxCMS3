<?php

/**
 * 前端日志
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;

class DebugAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SystemDebug';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '前端日志',
                'description' => '管理前端日志信息',
            ],
            'fun' => [
                'index' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'page,platform'
        ];
    }

}