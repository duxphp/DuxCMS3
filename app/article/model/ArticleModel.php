<?php

/**
 * 文章管理
 */

namespace app\article\model;

use app\system\model\SystemModel;

class ArticleModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'article_id',
        'validate' => [
            'class_id' => [
                'empty' => ['', '请选择分类!', 'must', 'all'],
            ],
        ],
        'format' => [
            'content' => [
                'function' => ['html_in', 'all'],
            ],
            'create_time' => [
                'function' => ['time', 'add'],
            ]
        ],
    ];

    protected function base($where) {
        $base = $this->table('article(A)')
            ->join('article_class(B)', ['B.class_id', 'A.class_id']);
        $field = ['A.*', 'B.name(class_name)'];
        return $base
            ->field($field)
            ->where((array)$where);
    }

    public function loadList($where = [], $limit = 0, $order = 'A.sort asc, A.create_time desc, A.article_id desc') {
        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        if (empty($list)) {
            return [];
        }
        return $list;
    }

    public function countList($where = []) {
        return $this->base($where)->count();
    }

    public function getWhereInfo($where) {
        return $this->base($where)->find();
    }

    public function getInfo($id) {
        $where = [];
        $where['A.article_id'] = $id;
        return $this->getWhereInfo($where);
    }

    public function _saveBefore($data) {
        if ($data['content'] && empty($data['description'])) {
            $data['description'] = \dux\lib\Str::strMake($data['content'], 250);
        }
        $data['keyword'] = trim($data['keyword']);
        $data['keyword'] = \dux\lib\Str::htmlClear($data['keyword']);
        $data['keyword'] = preg_replace ( "/\s(?=\s)/",',', $data['keyword']);
        $data['keyword'] = str_replace('，', ',',$data['keyword']);
        $keyword = explode(',', $data['keyword']);
        $tagsId = [];
        if (!empty($keyword)) {
            foreach ($keyword as $vo) {
                $vo = trim($vo);
                if(empty($vo)) {
                    continue;
                }
                $tagInfo = target('site/SiteTags')->getWhereInfo(['name' => $vo, 'app' => $data['app']]);
                if ($tagInfo) {
                    if (!target('site/SiteTags')->where(['tag_id' => $tagInfo['tag_id']])->setInc('quote', 1)) {
                        return false;
                    }
                    $tagId = $tagInfo['tag_id'];
                } else {
                    $tagId = target('site/SiteTags')->add(['name' => $vo, 'app' => $data['app']]);
                    if (!$tagId) {
                        return false;
                    }
                }
                $tagsId[] = $tagId;
            }
        }
        $data['tags_id'] = implode(',', $tagsId);
        return $data;

    }

}