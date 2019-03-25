<?php

/**
 * 推荐位管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\site\admin;

class PositionAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SitePosition';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '推荐位管理',
                'description' => '站点内容推荐位管理',
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
        return 'sort asc, pos_id asc';
    }

}