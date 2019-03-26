<?php

/**
 * 文章管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\article\admin;

class ContentAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'Article';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '文章管理',
                'description' => '管理站点中的文章信息',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
                'status' => true
            ]
        ];
    }

    public function _indexParam() {
        return [
            'class_id' => 'B.class_id',
            'status' => 'status',
            'keyword' => 'A.title'
        ];
    }

    protected function _indexWhere($whereMaps) {
        if (isset($whereMaps['status'])) {
            $whereMaps['A.status'] = $whereMaps['status'];
        }
        unset($whereMaps['status']);
        return $whereMaps;
    }

    public function _indexOrder() {
        return 'A.sort asc, A.create_time desc, A.article_id desc';
    }


    public function _indexAssign($pageMaps) {
        $classId = $pageMaps['class_id'];
        return array(
            'classList' => target('article/ArticleClass')->loadTreeList(),
            'classId' => $classId,
            'hookMenu' => [
                [
                    'name' => '全部',
                    'url' => url('index'),
                    'cur' => !isset($pageMaps['status']),
                ],
                [
                    'name' => '发布',
                    'url' => url('index', ['status' => 1]),
                    'cur' => isset($pageMaps['status']) && $pageMaps['status'] == 1,
                ],
                [
                    'name' => '草稿',
                    'url' => url('index', ['status' => 0]),
                    'cur' => isset($pageMaps['status']) && $pageMaps['status'] == 0,
                ],
            ],
        );
    }

    public function _addAssign() {
        $classId = request('get', 'class_id', 0, 'intval');
        return array(
            'classList' => target('article/ArticleClass')->loadTreeList(),
            'classId' => $classId
        );
    }

    public function _editAssign($info) {
        $classId = intval($info['class_id']);
        return array(
            'classList' => target('article/ArticleClass')->loadTreeList(),
            'classId' => $classId
        );
    }



}