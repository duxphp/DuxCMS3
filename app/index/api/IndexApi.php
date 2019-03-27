<?php

/**
 * 首页信息
 */

namespace app\index\api;

class IndexApi extends \app\base\api\BaseApi {

    protected $_middle = 'index/Index';

    public function index() {
        target($this->_middle, 'middle')->export(function ($data, $msg) {
            $this->success($msg, $data);
        }, function ($message, $code) {
            $this->error($message, $code);
        });
    }

}