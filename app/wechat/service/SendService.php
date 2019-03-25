<?php
namespace app\wechat\service;
/**
 * 发送接口
 */
class SendService {

    /**
     * 获取推送种类
     */
    public function getClassSend() {
        return [
            'wechat' => [
                'name' => '微信',
                'editor' => false,
            ],
        ];

    }

    /**
     * 获取推送结构
     */
    public function getTypeSend() {
        return array(
            'wechat' => array(
                'name' => '微信推送',
                'class' => 'wechat',
                'target' => 'wechat/Wechat',
                'var' => 1,
                'configRule' => array(
                )
            ),
        );
    }
}

