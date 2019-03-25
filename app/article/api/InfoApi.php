<?php

/**
 * 文章详情
 */

namespace app\article\api;

use \app\base\api\BaseApi;

class InfoApi extends BaseApi {

    protected $_middle = 'article/Info';

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