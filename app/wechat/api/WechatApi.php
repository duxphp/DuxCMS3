<?php

/**
 * 微信响应接口
 */

namespace app\wechat\api;

class WechatApi {

    public $wechat = null;
    public $config = [];

    public function __construct() {
        $target = target('wechat/Wechat', 'service');
        $target->init();
        $this->wechat = $target->wechat();
        $this->config = $target->config();
    }

    /**
     * 统一接口处理
     */
    public function index() {
        $server = $this->wechat->server;
        $server->push(function ($message) {
            $reply = '';
            //获取关注用户
            $connectUser = target('member/MemberConnect')->getWhereInfo([
                'type' => 'wechat',
                'open_id' => $message['FromUserName'],
            ]);
            //建立用户信息
            if (!empty($message['FromUserName'])) {
                $wechatUser = $this->wechat->user->get($message['FromUserName']);
                if (isset($wechatUser['nickname'])) {
                    target('member/Member', 'service')->oauthUser('wechat', $wechatUser['unionid'], $wechatUser['openid'], $wechatUser['nickname'], $wechatUser['headimgurl'], $wechatUser['sex']);
                    $connectUser = target('member/MemberConnect')->getWhereInfo([
                        'type' => 'wechat',
                        'open_id' => $message['FromUserName'],
                    ]);
                    target('member/MemberConnect')->edit(['connect_id' => $connectUser['connect_id'], 'follow' => 1]);
                }
            }
            if ($message['MsgType'] == 'event') {
                //关注信息
                if ($message['Event'] == 'unsubscribe' && empty($connectUser)) {
                    target('member/MemberConnect')->edit(['connect_id' => $connectUser['connect_id'], 'follow' => 0]);
                }
                $pregnancyName = '';
                //关注信息
                if ($message['Event'] == 'subscribe') {
                    //发送关注消息
                    $pregnancyName = $this->config['message_name'];
                    $text = new \EasyWeChat\Kernel\Messages\Text($this->config['message_focus']);
                    $this->wechat->customer_service->message($text)->to($message['FromUserName'])->send();
                }
            }
            return $reply;
        });


        $response = $server->serve();
        $response->send();
    }


}