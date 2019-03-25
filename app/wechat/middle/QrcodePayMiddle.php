<?php

/**
 * 微信支付
 */

namespace app\wechat\middle;


class QrcodePayMiddle extends \app\base\middle\BaseMiddle {



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

    protected function data() {
        $token = $this->params['token'];
        $data = $this->params['data'];
        $data['title'] = urldecode($data['title']);
        $data['body'] = urldecode($data['body']);
        unset($data['token']);
        if(!data_sign_has($data, $token)) {
            return $this->stop('token验证失败!');
        }
        $data['return_url'] = urldecode($data['return_url']);

        if($data['tmp'] + 600 < time()) {
            return $this->stop('支付已过期，请重新支付！');
        }

        //扫码支付
        $notifyUrl = url('api/wechat/WechatWeb/index');

        $config = target('wechat/WechatWeb', 'pay')->getConfig($notifyUrl);

        if(empty($config)) {
            return $this->stop('请先配置支付信息');
        }

        $money = $data['money'] ? $data['money'] : 0;
        $money = price_calculate($money, '*', 100, 0);
        $payData = [
            'body' => $data['title'] ? $data['title'] : $data['body'],
            'out_trade_no' => $data['order_no'],
            'total_fee' => $money,
            'attach' => urlencode(http_build_query(['app' => $data['app']])),
            'spbill_create_ip' => \dux\lib\Client::getUserIp(),
        ];
        if (empty($payData['out_trade_no'])) {
            return $this->stop('订单号不能为空!');
        }
        if ($payData['total_fee'] <= 0) {
            return $this->stop('支付金额不正确!');
        }
        if (empty($payData['body'])) {
            return $this->stop('支付信息描述不正确!');
        }
        if (empty($payData['attach'])) {
            return $this->stop('订单应用名不正确!');
        }
        try {
            $pay = \Yansongda\Pay\Pay::wechat($config)->scan($payData);
            return $this->run([
                'code' => $pay->code_url,
                'money' => $data['money'],
                'returnUrl' => $data['return_url'],
                'orderNo' => $data['order_no']
            ]);
        } catch (\Exception $e) {
            if($e->raw['err_code'] == 'ORDERPAID') {
                return $this->stop('支付成功!', 302, $data['return_url']);
            }
            return $this->stop($e->getMessage());
        }
    }

    protected function status() {
        $orderNo = $this->params['order_no'];
        if(empty($orderNo)) {
            return $this->stop('订单号不存在!');
        }

        $config = target('wechat/WechatWeb', 'pay')->getConfig();
        $pay = \Yansongda\Pay\Pay::wechat($config)->find($orderNo);
        if($pay->trade_state == 'SUCCESS') {
            return $this->run([], '订单支付成功!');
        }else {
            return $this->stop($pay->trade_state_desc);
        }
    }


}