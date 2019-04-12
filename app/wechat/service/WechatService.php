<?php

/**
 * 微信接口
 */

namespace app\wechat\service;
use EasyWeChat\Factory;

class WechatService extends \app\base\service\BaseService {

    public $wechat = null;
    public $config = [];

    /**
     * 实例化微信服务
     * WechatService constructor.
     */
    public function __construct() {
        if(empty($this->config)) {
            $this->init();
        }
    }

    /**
     * 初始化微信类
     * @param array $config
     * @return \EasyWeChat\OfficialAccount\Application|null
     */
    public function init($config = []) {
        $this->config = target('wechat/WechatConfig')->getConfig();
        $this->config = array_merge($this->config, $config);

        $options = [
            'app_id' => $this->config['appid'],
            'secret' => $this->config['secret'],
            'token' => $this->config['token'],
            'aes_key' => $this->config['aeskey'],
            'response_type' => 'array',
            'log' => [
                'level' => 'error',
                'permission' => 0775,
                'file'  => DATA_PATH . 'log/wechat_' . date('y-m-d') . '.log',
            ],
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => $this->config['oauth_url'] ? $this->config['oauth_url'] : url(LAYER_NAME . '/wechat/Login/connect'),
            ],
            'http' => [
                'timeout' => 20,
            ],
        ];
        $this->wechat = \EasyWeChat\Factory::officialAccount($options);
        return $this->wechat;
    }

    /**
     * 获取微信对象
     * @return null
     */
    public function wechat() {
        return $this->wechat;
    }

    /**
     * 获取配置文件
     * @return array
     */
    public function config() {
        return $this->config;
    }

    /**
     * 永久二维码
     * @param $params
     * @return mixed
     */
    public function perpetual($params) {
        $params = json_encode($params);
        $savePath = 'upload/qrcode/wechat_perpetual/';
        $filename = md5($params) . '.png';
        if (!is_file(ROOT_PATH . $savePath . $filename)) {
            $response = $this->wechat()->qrcode->forever($params);
            if(empty($response['ticket']) || empty($response['url'])) {
                return $this->error('生成失败！');
            }
            $image = \dux\lib\Http::doGet($response['url']);
            if(empty($image)) {
                return $this->error('二维码获取失败！');
            }
            if(!is_dir(ROOT_PATH . $savePath)) {
                mkdir(ROOT_PATH . $savePath, 0777, true);
            }
            if(!file_put_contents(ROOT_PATH . $savePath . $filename, $image)) {
                return $this->error('二维码保存失败！');
            }
        }

        return $this->success([
            'url' => DOMAIN_HTTP . ROOT_URL . '/' . $savePath . $filename,
            'file' => $savePath . $filename,
        ]);
    }

    /**
     * 临时二维码
     * @param $params
     * @param int $size
     * @return mixed
     */
    public function tmp($params, $day = 30) {
        $savePath = 'upload/qrcode/wechat_tmp/';
        $filename = md5($params) . '.png';

        $filePath = ROOT_PATH . $savePath . $filename;
        $status = false;
        if(is_file($filePath)) {
            $time = filemtime($filePath) + $day * 24 * 3600;
            if($time > time()) {
                $status = true;
            }
        }
        if (!$status) {
            $response = $this->wechat()->qrcode->temporary($params, $day * 24 * 3600);
            if(empty($response['ticket']) || empty($response['url'])) {
                return $this->error('生成失败！');
            }
            $image = \dux\lib\Http::doGet($response['url']);
            if(empty($image)) {
                return $this->error('二维码获取失败！');
            }
            if(!is_dir(ROOT_PATH . $savePath)) {
                mkdir(ROOT_PATH . $savePath, 0777, true);
            }
            if(!file_put_contents(ROOT_PATH . $savePath . $filename, $image)) {
                return $this->error('二维码保存失败！');
            }
        }
        return $this->run([
            'url' => DOMAIN_HTTP . ROOT_URL . '/' . $savePath . $filename,
            'file' => $savePath . $filename,
        ]);
    }
}

