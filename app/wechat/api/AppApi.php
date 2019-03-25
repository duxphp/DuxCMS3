<?php

/**
 * 小程序相关
 */

namespace app\wechat\api;

use \app\base\api\BaseApi;

class AppApi extends BaseApi {

    protected $_middle = 'wechat/App';

    public function login() {
        target($this->_middle, 'middle')->setParams([
            'app' => $this->apiApp
        ])->getUserInfo($this->data['code'])->export(function ($return) {
            $data = target('member/Member', 'service')->oauthUser('app', $return['unionid'], $return['openid'], $return['nickname'], $return['headimgurl'], $return['sex']);
            if(!$data) {
                $this->error(target('member/Member', 'service')->getError(), $data);
            }
            $this->success('ok', $data);
        }, function ($message, $code) {
            $this->error($message, $code);
        });
    }

}