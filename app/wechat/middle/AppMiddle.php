<?php

/**
 * APP接口相关
 */

namespace app\wechat\middle;

class AppMiddle extends \app\base\middle\BaseMiddle {

    public function getUserInfo($code) {
        $config = target('wechat/WechatApp')->getWhereInfo([
            'label' => $this->params['app']
        ]);
        if(empty($config)) {
            return $this-stop('应用未配置！');
        }
        $accessToken = new \Overtrue\Socialite\AccessToken(['access_token' => $code]);
        try {
            $socialite = new \Overtrue\Socialite\SocialiteManager([
                'wechat' => [
                    'client_id'     => $config['appid'],
                    'client_secret' => $config['secret'],
                ],
            ]);
            $wechat = $socialite->driver('wechat');
            $accessToken = $wechat->getAccessToken($code);
            $data = $wechat->user($accessToken)->getOriginal();
            return $this->run($data, 'ok');
        }catch (\Exception $exception) {
            return $this->stop($exception->getMessage());
        }
    }

}
