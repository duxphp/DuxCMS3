<?php
namespace app\article\api;

/**
 * 文章详情
 */

use \app\base\api\BaseApi;

class InfoApi extends BaseApi {

    protected $_middle = 'article/Info';

    /**
     * 文章内容详情
     * @method GET
     * @param inetger $id 文章ID
     * @return inetger $code 200
     * @return string $message ok
     * @return json $result {"pageInfo":{},"classInfo":{},"tagList":[],"info":[{"article_id": 1, "class_id": 1, "title": "标题", "keyword": "", "description": "", "image": "", "auth": "dux", "content": "文章内容", "create_time": 1546272000, "update_time": 1546272000, "virtual_view": 100, "view": 0, "sort": 0}]}
     * @field object $pageInfo 页面信息
     * @field object $classInfo 栏目信息
     * @field array $tagList Tag信息
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
        $id = $this->data['id'];
        target($this->_middle, 'middle')->setParams([
            'article_id' => $id,
        ])->data()->export(function ($data) {
            $this->success('ok', $data['info']);
        }, function ($message, $code) {
            $this->error($message, $code);
        });

    }


}