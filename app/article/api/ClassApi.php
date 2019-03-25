<?php

/**文章
 * 商品分类
 */

namespace app\article\api;

use \app\base\api\BaseApi;

class ClassApi extends BaseApi {

    protected $_middle = 'article/Category';

    /**
     * 列表
     */
    public function index() {

        target($this->_middle, 'middle')->treeList()->export(function ($data) {
            $this->success('ok', $data['treeList']);
        });

    }

}