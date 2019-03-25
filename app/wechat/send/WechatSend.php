<?php
namespace app\wechat\send;
/**
 * 微信消息服务
 */
class WechatSend extends \app\base\service\BaseService {

    /**
     * 检查数据
     * @param $data
     * @return bool
     */
    public function check($data) {
        if (!$data['user_status']) {
            return $this->error('微信推送必须为用户类型');
        }
        return $this->success();
    }

    /**
     * 发送服务
     * @param $info
     * @return bool
     */
    public function send($info) {

        if (empty($info['user_info'])) {
            $this->error('用户不存在！');
        }

        $openInfo = target('member/MemberConnect')->getWhereInfo([
            'type' => 'wechat',
            'user_id' => $info['receive'],
        ]);

        $info['param'] = json_decode($info['param'], true);

        if (empty($openInfo)) {
            return $this->error('没有绑定微信账号');
        }

        $target = target('wechat/Wechat', 'service');
        $target->init();
        $wechat = $target->wechat();

        $tpl = $info['param']['tpl'];
        $url = $info['param']['url'];
        $miniapp = $info['param']['miniapp'];

        unset($info['param']['tpl']);
        unset($info['param']['url']);
        unset($info['param']['miniapp']);

        try {
            $data = [
                'touser' => $openInfo['open_id'],
                'template_id' => $tpl,
                'url' => $url,
                'data' => $info['param'],
            ];

            if ($miniapp) {
                $data['miniprogram'] = [
                    'appid' => $miniapp['appid'],
                    'pagepath' => $miniapp['path'],
                ];
            }
            $wechat->template_message->send($data);
        } catch (\Exception $err) {
            return $this->error($err->getMessage());
        }
        return $this->success();

    }

}