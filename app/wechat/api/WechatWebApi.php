<?php

/**
 * 扫码支付
 */

namespace app\wechat\api;

class WechatWebApi {

    /**
     * 异步回调
     */
    public function index() {
        $data = file_get_contents("php://input");
        $data = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        parse_str(urldecode($data['attach']), $params);
        $app = $params['app'];
        if(empty($app)) {
            dux_log('回调参数获取失败');
            dux_log($params);
            return false;
        }

        $config = target('wechat/WechatWeb', 'pay')->getConfig('');
        try{
            $wechat = \Yansongda\Pay\Pay::wechat($config);
            $data = $wechat->verify();
            if ($data['return_code'] <> 'SUCCESS') {
                dux_log('支付状态失败');
                return false;
            }
            $orderNo = $data['out_trade_no'];
            if (empty($orderNo)) {
                dux_log('支付号错误');
                return false;
            }
            $model = target('member/PayRecharge');

            dux_log($app);

            $callbackList = target('member/PayConfig')->callbackList();
            $callbackInfo = $callbackList[$app];

            $model->beginTransaction();
            if(!target($callbackInfo['target'], 'service')->pay($orderNo, price_calculate($data['total_fee'], '/', 100), '微信支付', $data['transaction_id'], 'wechat_web')) {
                $model->rollBack();
                dux_log(target($callbackInfo['target'], 'service')->getError());
                return false;
            }
            $model->commit();
            return $wechat->success()->send();
        } catch (\Exception $e) {
            dux_log($e->getMessage());
        }
    }

}