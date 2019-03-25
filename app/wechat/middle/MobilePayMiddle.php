<?php

/**
 * 微信支付
 */

namespace app\wechat\middle;


class MobilePayMiddle extends \app\base\middle\BaseMiddle {

    protected function meta() {
        $this->setMeta('微信支付');
        $this->setName('微信支付');
        $this->setCrumb([
            [
                'name' => '微信支付',
                'url' => url()
            ]
        ]);

        return $this->run([
            'pageInfo' => $this->pageInfo
        ]);
    }

}