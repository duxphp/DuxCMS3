<?php

namespace app\article\api;

/**
 * 文章分类
 */

use \app\base\api\BaseApi;

class ClassApi extends BaseApi {

    protected $_middle = 'article/Category';

    /**
     * 获取分类列表
     * @method GET
     * @param inetger $id 上级分类ID
     * @return inetger $code 200
     * @return string $message json示例
     * @return json $result {"pageList": [{"id": "分类ID", "name": "名称"}]}
     * @field string $id 栏目id 
     * @field inetger $name 栏目名称
     */
    public function index() {

        target($this->_middle, 'middle')->treeList()->export(function ($data) {
            $this->success('ok', $data['treeList']);
        });

    }

}