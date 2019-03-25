<?php
namespace app\wechat\pay;
/**
 * 微信移动端服务
 */
class WechatWebPay extends \app\base\service\BaseService {

    private $rsa = '';
    private $name = 'wechat_web';

    public function getConfig($notifyUrl = '') {
        $config = target('member/PayConfig')->getConfig($this->name);
        if (empty($config['mch_id']) || empty($config['md5_key'])) {
            return $this->error('请先配置支付接口信息!');
        }
        $notifyUrl = DOMAIN . $notifyUrl;
        return [
            'app_id' => $config['app_id'],
            'mch_id' => $config['mch_id'],
            'key' => $config['md5_key'],
            'cert_client' => ROOT_PATH . $config['app_cert_pem_file'],
            'cert_key' => ROOT_PATH . $config['app_key_pem_file'],
            'notify_url' => $notifyUrl,
        ];
    }

    public function getData($data, $returnUrl) {
        if (empty($data)) {
            return $this->error('订单数据未提交!');
        }
        unset($data['user_id']);
        $data['return_url'] = urlencode(DOMAIN . $returnUrl);
        $data['tmp'] = time();
        $data['token'] = data_sign($data);
        $url = url('controller/wechat/WebPay/index', [], false, true, false) . '?' . http_build_query($data);
        return $this->success([
            'url' => $url
        ]);
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
            return $this->error($e->getMessage());
        }
    }

}