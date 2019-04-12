<?php

/**
 * 公众号相关
 */

namespace app\wechat\middle;

class WechatMiddle extends \app\base\middle\BaseMiddle {

    public function wechat() {
        return target('wechat/Wechat', 'service')->init();
    }

    protected function perpetual($params) {
        $data = target('wechat/Wechat', 'service')->perpetual($params);
        if(!$data) {
            return $this->stop(target('wechat/Wechat', 'service')->getError());
        }
        return $this->run($data);
    }

    protected function tmp($params) {
        $data = target('wechat/Wechat', 'service')->tmp($params);
        if(!$data) {
            return $this->stop(target('wechat/Wechat', 'service')->getError());
        }
        return $this->run($data);
    }

}
