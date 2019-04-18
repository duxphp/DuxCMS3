<?php

/**
 * 配置模块
 */

namespace app\site\api;

use \app\base\api\BaseApi;

class ConfigApi extends BaseApi {

    public function index() {
        $config = target('site/SiteConfig')->getConfig();
        $this->success('ok', $config);
    }

}