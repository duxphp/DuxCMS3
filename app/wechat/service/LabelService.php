<?php

namespace app\wechat\service;

/**
 * 标签接口
 */
class LabelService {

    /**
     * 栏目列表
     */
    public function attention() {

        if (!isWechat()) {
            return false;
        }

        $wechat = target('wechat/Wechat', 'service')->init();
        $userId = target('member/MemberUser')->getUid();

        if(empty($userId)) {
            return false;
        }

        $openInfo = target('member/MemberConnect')->getWhereInfo([
            'user_id' => $userId
        ]);
        if(empty($openInfo)) {
            return false;
        }

        $info = $wechat->user->get($openInfo['open_id']);
        return $info['subscribe'];
    }


}
