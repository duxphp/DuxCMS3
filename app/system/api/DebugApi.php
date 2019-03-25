<?php

/**
 * 调试信息
 */

namespace app\system\api;

use \app\base\api\BaseApi;

class DebugApi extends BaseApi {

    protected $_middle = 'system/Debug';

    public function push() {
        target($this->_middle, 'middle')->setParams([
            'platform' => PLATFORM,
            'page' => $this->data['page'],
            'content' => $this->data['content'],
        ])->data()->export(function ($data) {
            $this->success('ok', $data);
        }, function ($message, $code) {
            $this->error($message, $code);
        });


    }

}