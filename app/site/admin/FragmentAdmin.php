<?php

/**
 * 碎片管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\site\admin;

class FragmentAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SiteFragment';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '碎片管理',
                'description' => '管理站点内碎片信息',
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
        return 'fragment_id asc';
    }

}