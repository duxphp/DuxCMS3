<?php
namespace app\tools\send;
/**
 * APP发送服务
 */
class SiteSend extends \app\base\service\BaseService {

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
        $config = target('tools/ToolsSendConfig')->getConfig('site');
        if (empty($config)) {
            return $this->error('配置不存在!');
        }
        $receive = $info['receive'];
        $status = target('member/MemberNotice')->add([
            'user_id' => $receive,
            'title' => $info['title'],
            'content' => $info['content'],
            'time' => time(),
        ]);
        return $this->success();
    }

}