<?php

namespace app\article\api;

/**
 * 文章列表
 */

class ListApi extends \app\base\api\BaseApi {

    protected $_middle = 'article/List';
    
    /**
     * 获取文章列表
     * @method GET
     * @return inetger $code 200
     * @return string $message json示例
     * @return json $result [{"name":"名称", "class_id": "栏目ID"}]
     */
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