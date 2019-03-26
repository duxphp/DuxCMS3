<?php

/**
 * 内容详情
 */

namespace app\page\api;

use \app\base\api\BaseApi;

class InfoApi extends BaseApi {

    protected $_middle = 'page/Info';

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