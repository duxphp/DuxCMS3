<?php
namespace app\wechat\service;
/**
 * 会员处理
 */
class MemberService extends \app\base\service\BaseService {

    /**
     * 检查客户端
     * @return bool
     */
    public function checkClient() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }


}
