<?php

/**
 * 微信工具
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class UtilAdmin extends \app\system\admin\SystemExtendAdmin {

    public function image() {
        $url = request('get', 'url');
        $content = \dux\lib\Http::doGet($url, 10, 'Referer: http://www.qq.com/');
        header('Content-Type:image/jpg');
        echo $content;
        exit();
    }


}