<?php

/**
 * 小程序接口
 */

namespace app\wechat\service;

class MiniappService extends \app\base\service\BaseService {

    public $wechat = null;
    public $config = [];

    /**
     * 实例化微信服务
     * MiniappService constructor.
     */
    public function __construct() {
        if(empty($this->config)) {
            $this->init();
        }
    }

    /**
     * 初始化微信类
     * @param array $config
     * @param string $label
     * @return \EasyWeChat\MiniProgram\Application
     */
    public function init($config = [], $label = 'main') {
        $this->config = target('wechat/WechatMiniapp')->getWhereInfo(['label' => $label]);
        $this->config = array_merge($this->config, $config);
        $options = [
            'app_id' => $this->config['appid'],
            'secret' => $this->config['secret'],
            'response_type' => 'array',
            'log' => [
                'level' => 'error',
                'permission' => 0775,
                'file'  => DATA_PATH . 'log/miniapp_' . date('y-m-d') . '.log',
            ]
        ];
        return \EasyWeChat\Factory::miniProgram($options);
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
}

