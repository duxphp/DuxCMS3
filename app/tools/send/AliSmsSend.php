<?php

namespace app\tools\send;
error_reporting(0);//关闭错误报告
/**
 * 短信发送服务
 */
class AliSmsSend extends \app\base\service\BaseService {

    /**
     * 检查数据
     * @param $data
     * @return bool
     */
    public function check($data) {
        if($data['user_status']) {
            return $this->success();
        }
        if (!preg_match("/(^1[3|4|5|7|8][0-9]{9}$)/", $data['receive'])) {
            return $this->error('手机号码不正确');
        }
        return $this->success();
    }

    /**
     * 发送接口
     * @param $info
     * @return bool
     */
    public function send($info) {
        $config = target('tools/ToolsSendConfig')->getConfig('alsms');
        if(empty($config)){
            return $this->error('配置不存在!');
        }
		$receive = $info['receive'];
        if($info['user_status']) {
			$userInfo = target('member/MemberUser')->getUser($info['receive']);
            $receive = $userInfo['tel'];
        }
		$params = json_decode($info['param'], true);
        if(!$params['tpl']) {
            return $this->error('请配置短信模板');
        }
		if($params['url']){
			unset($params['url']);
		}
		$url = $this->alisms($receive, $config['apiid'], $config['apikey'], $config['name'], $params['tpl'], $params);
		$return = \dux\lib\Http::curlGet($url);
        $return = json_decode($return, true);
        if($return['Code'] == 'OK'){
            return $this->success();
        }else{
            return $this->error($return['Code'] . $return['Message']);
        }
    }
	
	/**
	*
	*$phon 电话号码
	*$AccessKeyId 密钥
	*$accessKeySecret 密匙
	*$SignName 签名
	*$TemplateCode 模版id
	*$TemplateParam array 模版值
	*
	*/
	public function alisms($phone, $AccessKeyId, $accessKeySecret, $SignName, $TemplateCode, $TemplateParam, $domain = 'dysmsapi.aliyuncs.com') {
		$apiParams["PhoneNumbers"] = $phone; //手机号
		$apiParams["SignName"] = $SignName; //签名
		$apiParams["TemplateCode"] = $TemplateCode; //短信模版id
		$apiParams["TemplateParam"] = json_encode($TemplateParam);  //模版内容
		$apiParams["AccessKeyId"] = $AccessKeyId; //key
		$apiParams["RegionId"] = "cn-hangzhou"; //固定参数
		$apiParams["Format"] = "json";  //返回数据类型,支持xml,json
		$apiParams["SignatureMethod"] = "HMAC-SHA1"; //固定参数
		$apiParams["SignatureVersion"] = "1.0";  //固定参数
		$apiParams["SignatureNonce"] = uniqid(); //用于请求的防重放攻击，每次请求唯一
		date_default_timezone_set("GMT"); //设置时区
		$apiParams["Timestamp"] = date('Y-m-d\TH:i:s\Z'); //格式为：yyyy-MM-dd’T’HH:mm:ss’Z’；时区为：GMT
		$apiParams["Action"] = 'SendSms'; //api命名 固定值
		$apiParams["Version"] = '2017-05-25'; //api版本 固定值
		$apiParams["Signature"] = $this->computeSignature($apiParams, $accessKeySecret);  //最终生成的签名结果值
			
		$requestUrl = "http://". $domain . "/?";
		foreach ($apiParams as $apiParamKey => $apiParamValue) {
			$requestUrl .= "$apiParamKey=" . urlencode($apiParamValue) . "&"; 
		}
		
		return $requestUrl;
	}
	
	public function computeSignature($parameters, $accessKeySecret) {
		ksort($parameters); 
		$canonicalizedQueryString = ''; 
		foreach ($parameters as $key => $value) {
			$canonicalizedQueryString .= '&' . $this->percentEncode($key) . '=' . $this->percentEncode($value); 
		}	
		$stringToSign = 'GET&%2F&' . $this->percentencode(substr($canonicalizedQueryString, 1)); 
		$signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . "&", true)); ; 
		return $signature; 
	}
	
	public function percentEncode($str) {
		$res = urlencode($str); 
		$res = preg_replace('/\+/', '%20', $res); 
		$res = preg_replace('/\*/', '%2A', $res); 
		$res = preg_replace('/%7E/', '~', $res); 
		return $res; 
	}
	
	public function curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$httpResponse=curl_exec($ch);
		if($httpResponse){
			return json_decode($httpResponse);
		}else{
			return json_decode(curl_error($ch));
		}
		curl_close($ch);
	}

}
