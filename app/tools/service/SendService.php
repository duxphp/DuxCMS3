<?php
namespace app\tools\service;
/**
 * 发送接口
 */
class SendService {

    /**
     * 获取推送种类
     */
    public function getClassSend() {
        return [
            'sms' => [
                'name' => '短信',
                'editor' => false,
            ],
            'mail' => [
                'name' => '邮件',
                'common' => 'html',
                'editor' => true,
            ],
            'app' => [
                'name' => 'APP',
                'editor' => false,
            ],

        ];

    }

    /**
     * 获取推送结构
     */
    public function getTypeSend() {
        return array(
            'email' => array(
                'name' => 'SMTP邮件',
                'target' => 'tools/Email',
                'class' => 'mail',
                'configRule' => array(
                    'smtp_host' => '发信地址',
                    'smtp_port' => '发信端口',
                    'smtp_ssl' => '安全链接',
                    'smtp_username' => '发信用户',
                    'smtp_password' => '发信密码',
                    'smtp_from_to' => '发信邮箱',
                    'smtp_from_name' => '发件人',
                )
            ),
            'almail' => array(
                'name' => '阿里邮件',
                'target' => 'tools/AliMail',
                'class' => 'mail',
                'configRule' => array(
                    'id' => 'API账号',
                    'key' => 'API密钥',
                    'mail' => '发信地址',
                )
            ),
			'alsms' => array(
                'name' => '阿里短信',
                'target' => 'tools/AliSms',
                'class' => 'sms',
                'var' => 1,
                'configRule' => array(
                    'apiid' => 'API账号',
                    'apikey' => 'API密码',
                    'name' => '签名',
                )
            ),
            'xiaomi' => array(
                'name' => '小米推送',
                'target' => 'tools/Xiaomi',
                'class' => 'app',
                'configRule' => array(
                    'ios_key' => 'IOS密钥',
                    'android_key' => '安卓密钥',
                    'android_name' => '安卓包名',
                )
            ),
            'site' => array(
                'name' => '会员通知',
                'target' => 'tools/Site',
                'class' => 'site',
                'configRule' => array(
                )
            ),
        );
    }
}

