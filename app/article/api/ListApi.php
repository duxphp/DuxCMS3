<?php

/**
 * 文章列表
 */

namespace app\article\api;

class ListApi extends \app\base\api\BaseApi {

    protected $_middle = 'article/List';

    public function index() {
        $pageLimit = $this->data['limit'] ? $this->data['limit'] : 10;
        $classId = $this->data['id'];
        $keyword = $this->data['keyword'];
        $tag = $this->data['tag'];

        target($this->_middle, 'middle')->setParams([
            'classId' => $classId,
            'keyword' => $keyword,
            'tag' => $tag,
            'limit' => $pageLimit,
        ])->data()->export(function ($data) use ($pageLimit) {
            if(!empty($data['pageList'])) {
                $this->success('ok', [
                    'data' => $data['pageList'],
                    'pageData' => $this->pageData($pageLimit, $data['pageList'], $data['pageData']),
                ]);
            }else {
                $this->error('暂无更多文章', 404);
            }
        }, function ($message, $code, $url) {
            $this->error('暂无更多文章', 404);
        });

    }

}