<?php

/**
 * 搜索管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\site\admin;

class SearchAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SiteSearch';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '搜索管理',
                'description' => '管理站点中搜索概况',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'status' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'keyword',
            'type' => 'app'
        ];
    }

    public function _indexAssign($pageMaps) {
        return [
            'typeList' => target('site/SiteSearch')->typeList()
        ];
    }

    public function _addAssign() {
        return [
            'typeList' => target('site/SiteSearch')->typeList()
        ];
    }

    public function _editAssign($info) {
        return [
            'typeList' => target('site/SiteSearch')->typeList()
        ];
    }

}