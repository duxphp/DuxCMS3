<?php

/**
 * 自定义列表
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\site\admin;

class DiyAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SiteDiy';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '自定义列表',
                'description' => '管理站点自定义列表信息',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'name'
        ];
    }

    public function _indexOrder() {
        return 'diy_id asc';
    }

}