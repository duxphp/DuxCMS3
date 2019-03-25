<?php
namespace app\tools\send;
/**
 * APP发送服务
 */
class XiaomiSend extends \app\base\service\BaseService {

    /**
     * 检查数据
     * @param $data
     * @return bool
     */
    public function check($data) {
        if ($data['user_status']) {
            return $this->success();
        }

        $userId = intval($data['receive']);
        if (!$userId) {
            return $this->error('账户ID不正确');
        }

        return $this->success();
    }

    /**
     * 发送接口
     * @param $info
     * @return bool
     */
    public function send($info) {
        $config = target('tools/ToolsSendConfig')->getConfig('xiaomi');
        if (empty($config)) {
            return $this->error('配置不存在!');
        }
        $receive = $info['receive'];
        try {
            \Mipush\Push::init(
                ['secret' => $config['ios_key']],
                ['secret' => $config['android_key'], 'package_name' => $config['android_name']],
                [
                    'title' => $info['title'],
                    'pass_through' => 0,
                    'notify_type' => -1,
                    'time_to_send' => 0,
                ],
                'product'
            );
            $res = \Mipush\Push::toUse('alias', [
                'alias' => [$receive],
                'description' => $info['title']
            ]);
            if(empty($res)) {
                $this->error('接口返回数据为空');
            }
            if(!$res['ios']) {
                return $this->error('ios推送失败Null！');
            }
            if(!$res['android']) {
                return $this->error('android推送失败Null！');
            }
            if($res['ios']['result'] <> 'ok') {
                return $this->error($res['ios']['reason'] . $res['ios']['description']);
            }
            if($res['android']['result'] <> 'ok') {
                return $this->error($res['android']['reason'] . $res['android']['description']);
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}