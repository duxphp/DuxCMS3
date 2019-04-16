<?php

namespace app\article\api;

/**
 * 文章列表
 */

class ListApi extends \app\base\api\BaseApi {

    protected $_middle = 'article/List';
    
    /**
     * 文章内容列表
     * @method GET
     * @param inetger $id 分类ID，可选
     * @param inetger $keyword 搜索关键词，可选
     * @param inetger $tag Tag词，可选
     * @param inetger $limit 每页数量，默认10
     * @return inetger $code 200
     * @return string $message ok
     * @return json $result {"pageInfo": {}, "classInfo": {},  "countList":1, "tagInfo":{}, "pageData": {"limit": 10, "page": 1, "totalPage": 1, "raw": {}}, "pageList":[{"article_id": 1, "class_id": 1, "title": "标题", "keyword": "", "description": "", "image": "", "auth": "dux", "content": "文章内容", "create_time": 1546272000, "update_time": 1546272000, "virtual_view": 100, "view": 0, "sort": 0}]}
     * @field object $pageInfo 页面信息
     * @field object $classInfo 栏目信息
     * @field inetger $countList 文章总数
     * @field object $tagInfo Tag信息
     * @field object $pageData 分页数据
     * @field inetger $article_id 文章ID 
     * @field inetger $class_id 上级ID
     * @field string $title 文章标题 
     * @field string $keyword 文章关键词 
     * @field string $description 文章描述 
     * @field string $image 封面图 
     * @field string $auth 作者
     * @field string $content 内容 
     * @field string $create_time 创建时间 
     * @field string $update_time 更新时间 
     * @field string $virtual_view 虚拟浏览量 
     * @field string $view 真实浏览量 
     * @field inetger $sort 顺序 
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