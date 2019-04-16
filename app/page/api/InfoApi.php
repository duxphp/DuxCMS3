<?php

namespace app\page\api;

/**
 * 单页面
 */

use \app\base\api\BaseApi;

class InfoApi extends BaseApi {

    protected $_middle = 'page/Info';

    /**
     * 单页面内容
     * @method GET
     * @param inetger $id 页面ID，可选
     * @return inetger $code 200
     * @return string $message ok
     * @return json $result {"pageInfo": {}, "tagList": [], "info": [{"page_id": 1, "parent_id": 0, "name": "标题", "keyword": "", "description": "", "image": "",  "content": "页面内容", "create_time": 1546272000, "update_time": 1546272000, "virtual_view": 100, "view": 0, "sort": 0}]}
     * @field object $pageInfo 页面信息
     * @field array $tagList tag信息
     * @field inetger $page_id 页面ID
     * @field inetger $parent_id 上级ID
     * @field string $name 页面标题
     * @field string $image 页面关键词
     * @field string $description 页面描述
     * @field string $image 封面图
     * @field string $content 内容
     * @field string $create_time 创建时间
     * @field string $update_time 更新时间
     * @field string $virtual_view 虚拟浏览量
     * @field string $view 真实浏览量
     * @field inetger $sort 顺序
     */
    public function index() {
        $id = request('get', 'id', 0, 'intval');
        target($this->_middle, 'middle')->setParams([
            'id' => $id,
        ])->meta()->classInfo()->data()->export(function ($data) {
            $this->success('ok', $data);
        }, function ($message, $code) {
            $this->error($message, $code);
        });
    }


}