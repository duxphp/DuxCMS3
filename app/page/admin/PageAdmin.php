<?php

/**
 * 页面管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\page\admin;

class PageAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'Page';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '页面管理',
                'description' => '管理系统中单页面',
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

    public function _indexPage() {
        return 100;
    }

    protected function _addAssign() {
        return array(
            'classList' => target('page/Page')->loadTreeList(),
        );
    }

    protected function _editAssign($info) {
        return array(
            'classList' => target('page/Page')->loadTreeList(),
        );
    }

    public function _indexData($where, $limit, $order) {
        return target($this->_model)->loadTreeList($where, $limit, $order);
    }

    protected function _delBefore($id) {
        $cat = target($this->_model)->loadTreeList([], 0, '', $id);
        if ($cat) {
            $this->error('清先删除子分类!');
        }
    }

}