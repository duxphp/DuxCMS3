<?php
namespace app\system\api;

/**
 * 调试信息
 */

use \app\base\api\BaseApi;

class DebugApi extends BaseApi {

    protected $_middle = 'system/Debug';

    /**
     * 前端调试记录
     * @method GET
     * @param string $platform 平台名
     * @param string $page 页面地址
     * @param string $content 调试内容
     * @return inetger $code 200
     * @return string $message ok
     * @return json $result {}
     */
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