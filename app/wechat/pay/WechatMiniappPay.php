<?php
namespace app\wechat\pay;
/**
 * 微信移动端服务
 */
class WechatMiniappPay extends \app\base\service\BaseService {

    private $name = 'wechat_miniapp';

    public function getConfig() {
        $config = target('member/PayConfig')->getConfig($this->name);
        if (empty($config['mch_id']) || empty($config['md5_key'])) {
            return $this->error('请先配置支付接口信息!');
        }
        $notifyUrl = url('api/wechat/WechatMiniapp/index',[], true);
        return [
            'miniapp_id' => $config['appid'],
            'app_id' => $config['app_id'],
            'mch_id' => $config['mch_id'],
            'key' => $config['md5_key'],
            'type' => 'miniapp',
            'cert_client' => ROOT_PATH . $config['app_cert_pem_file'],
            'cert_key' => ROOT_PATH . $config['app_key_pem_file'],
            'notify_url' => $notifyUrl,
        ];
    }

    public function getData($data) {
        if (empty($data)) {
            return $this->error('订单数据未提交!');
        }
        $config = $this->getConfig();
        if (!$config) {
            return false;
        }
        $money = $data['money'] ? $data['money'] : 0;
        $money = price_calculate($money, '*', 100, 0);
        $payData = [
            'openid' => $data['open_id'],
            'body' => $data['title'] ? $data['title'] : $data['body'],
            'out_trade_no' => $data['order_no'],
            'total_fee' => $money,
            'attach' => urlencode(http_build_query(['app' => $data['app']])),
            'spbill_create_ip' => \dux\lib\Client::getUserIp(),
        ];
        if (empty($payData['out_trade_no'])) {
            return $this->error('订单号不能为空!');
        }
        if ($payData['total_fee'] <= 0) {
            return $this->error('支付金额不正确!');
        }
        if (empty($payData['body'])) {
            return $this->error('支付信息描述不正确!');
        }
        if (empty($payData['attach'])) {
            return $this->error('订单应用名不正确!');
        }
        try {
            $pay = \Yansongda\Pay\Pay::wechat($config)->miniapp($payData);
            return $this->success([
                'appId' => $pay->appId,
                'timeStamp' => $pay->timeStamp,
                'nonceStr' => $pay->nonceStr,
                'package' => $pay->package,
                'signType' => $pay->signType,
                'paySign' => $pay->paySign,
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function transfer($data) {
        if($data['open_id']) {
            $config = target('member/PayConfig')->typeInfo($this->name);
            $oathUser = target('member/MemberConnect')->getWhereInfo([
                'user_id' => $data['user_id'],
                'type' => $config['oauth']
            ]);
            if(empty($oathUser)) {
                return $this->error('该用户未绑定微信');
            }
            $data['open_id'] = $oathUser['open_id'];
        }
        $payData = [
            'partner_trade_no' => $data['pay_no'],
            'openid' => $data['open_id'],
            'check_name' => 'NO_CHECK',
            'amount' => price_calculate($data['money'], '*', 100, 0),
            'desc' => $data['remark'],
            'type' => 'miniapp'
        ];
        if ($payData['amount'] <= 0) {
            return $this->error('转账金额不正确!');
        }
        $config = $this->getConfig();
        try {
            $return = \Yansongda\Pay\Pay::wechat($config)->transfer($payData);
            return $this->success($return['payment_no']);
        } catch (\Exception $e) {
            dux_log(json_encode($e));
            return $this->error($e->getMessage());
        }

    }

    public function refund($data) {
        $payData = [
            'transaction_id' => $data['pay_no'],
            'total_fee' => price_calculate($data['total_money'], '*', 100, 0),
            'refund_fee' => price_calculate($data['money'], '*', 100, 0),
            'out_refund_no' => log_no(),
        ];
        if ($payData['refund_fee'] <= 0) {
            return $this->error('退款金额不正确!');
        }
        if (empty($payData['out_trade_no']) && empty($payData['out_refund_no'])) {
            return $this->error('退款单号不正确!');
        }
        $config = $this->getConfig();
        try {
            $return = \Yansongda\Pay\Pay::wechat($config)->refund($payData);
            return $this->success($return['refund_id']);
        } catch (\Exception $e) {
            dux_log(json_encode($e));
            return $this->error($e->getMessage());
        }
    }

}
