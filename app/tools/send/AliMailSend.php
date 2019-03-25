<?php
namespace app\tools\send;
/**
 * 阿里邮件
 */
class AliMailSend extends \app\base\service\BaseService {




    /**
     * 检查数据
     * @param $data
     * @return bool
     */
    public function check($data) {
        if ($data['user_status']) {
            return $this->success();
        }
        if (!filter_var($data['receive'], \FILTER_VALIDATE_EMAIL)) {
            return $this->error('邮箱账号不正确');
        }
        return $this->success();
    }

    /**
     * 发送接口
     * @param $info
     * @return bool
     */
    public function send($info) {
        $config = target('tools/ToolsSendConfig')->getConfig('almail');
        if (empty($config)) {
            return $this->error('配置不存在!');
        }
        $receive = $info['receive'];
        if ($info['user_info']) {
            $receive = $info['user_info']['email'];
        }

        $apiParams = [];
        //公共参数

        $apiParams["AccessKeyId"] = $config['id'];
        $apiParams["Format"] = 'JSON';
        $apiParams["SignatureMethod"] = 'HMAC-SHA1';
        $apiParams["SignatureVersion"] = '1.0';
        $apiParams["SignatureNonce"] = uniqid();
        date_default_timezone_set("GMT");
        $apiParams["Timestamp"] = date('Y-m-d\TH:i:s\Z');
        $apiParams["Version"] = '2015-11-23';
        //接口参数
        $apiParams["Action"] = 'SingleSendMail';
        $apiParams["TagName"] = 'duxphp';
        $apiParams['AddressType'] = 0;
        $apiParams['AccountName'] = $config['mail'];
        $apiParams['ReplyToAddress'] = 'true';
        $apiParams['ToAddress'] = $receive;
        $apiParams['Subject'] = $info['title'];
        $apiParams['HtmlBody'] = html_out($info['content']);

        $apiParams["Signature"] = $this->computeSignature($apiParams, $config['key']);

        $url = "http://dm.aliyuncs.com/";

        $return = \dux\lib\Http::curlPost($url, $apiParams, 10);

        $return = json_decode($return, true);
        if ($return['EnvId']) {
            return $this->success();
        } else {
            return $this->error(print_r($return, true));
        }
    }

    protected function computeSignature($parameters, $accessKeySecret) {
        ksort($parameters);
        $canonicalizedQueryString = '';
        foreach ($parameters as $key => $value) {
            $canonicalizedQueryString .= '&' . $this->percentEncode($key) . '=' . $this->percentEncode($value);
        }
        $stringToSign = 'POST&%2F&' . $this->percentEncode(substr($canonicalizedQueryString, 1));
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . "&", true));
        return $signature;
    }

    protected function percentEncode($str) {
        $res = urlencode($str);
        $res = preg_replace('/\+/', '%20', $res);
        $res = preg_replace('/\*/', '%2A', $res);
        $res = preg_replace('/%7E/', '~', $res);
        return $res;
    }

}