<?php

/**
 * 配置模块
 */

namespace app\site\api;

use \app\base\api\BaseApi;

class ConfigApi extends BaseApi {

    public function index() {
        $config = target('site/SiteConfig')->getConfig();
        //wechatLogin
        $config['wechat_wap_login'] = url('mobile/wechat/Login/index', [], true);
        $this->success('ok', $config);
    }

}