<?php

/**
 * 分类管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\article\admin;

class ClassAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'ArticleClass';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '文章分类',
                'description' => '文章分类管理',
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

    protected function _indexWhere($whereMaps) {
        return $whereMaps;
    }

    public function _indexPage() {
        return 100;
    }

    public function _indexData($where, $limit, $order) {
        return target($this->_model)->loadTreeList($where, $limit, $order);
    }

    protected function _indexAssign($pageMaps) {
        return array(
            'classList' => target('article/ArticleClass')->loadTreeList(),
        );
    }

    protected function _addAssign() {
        return array(
            'classList' => target('article/ArticleClass')->loadTreeList(),
        );
    }

    protected function _editAssign($info) {
        return array(
            'classList' => target('article/ArticleClass')->loadTreeList(),
        );
    }

    protected function _delBefore($id) {
        $cat = target($this->_model)->loadTreeList([], 0, '', $id);
        if ($cat) {
            $this->error('清先删除子分类!');
        }
        $count = target('article/Article')->countList([
            'A.class_id' => $id
        ]);
        if ($count > 0) {
            $this->error('请先删除该分类下的内容！');
        }
    }

}