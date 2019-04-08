<?php

/**
 * 小程序相关
 */

namespace app\wechat\api;

use \app\base\api\BaseApi;

class MiniappApi extends BaseApi {

    protected $_middle = 'wechat/Miniapp';

    public function login() {
        if(empty($this->data['iv']) || empty($this->data['encryptedData'])) {
            $this->error('请进行用户授权');
        }
        target($this->_middle, 'middle')->setParams([
            'app' => $this->apiApp
        ])->getUserInfo($this->data['code'], $this->data['iv'], $this->data['encryptedData'])->export(function ($return) {
            $data = target('member/Member', 'service')->oauthUser('miniapp', $return['unionid'], $return['openid'], $this->data['nickname'], $this->data['avatar'], $this->data['sex']);
            if(!$data) {
                $this->error(target('member/Member', 'service')->getError(), $data);
            }
            $this->success('ok', $data);
        }, function ($message, $code) {
            $this->error($message, $code);
        });
    }

}