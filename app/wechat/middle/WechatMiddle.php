<?php

/**
 * 公众号相关
 */

namespace app\wechat\middle;

class WechatMiddle extends \app\base\middle\BaseMiddle {

    public function wechat() {
        return target('wechat/Wechat', 'service')->init();
    }

    //永久二维码
    protected function perpetual($params) {

        if(is_array($params))
            $params = json_encode($params);

        $savePath = 'upload/qrcode/wechat_perpetual/';
        $filename = md5($params) . '.png';
        if (!is_file(ROOT_PATH . $savePath . $filename)) {
            $response = $this->wechat()->qrcode->forever($params);
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $response->saveAs(ROOT_PATH . $savePath, $filename);
            }
        }
        return $this->run([
            'url' => DOMAIN_HTTP . ROOT_URL . '/' . $savePath . $filename,
            'file' => $savePath . $filename,
        ]);
    }

    //临时二维码
    protected function tmp($path, $parameter, $size = 430) {
        $savePath = 'upload/qrcode/wechat_tmp/';
        $filename = md5($path . $parameter) . '.png';
        if (!is_file(ROOT_PATH . $savePath . $filename)) {
            $response = $this->wechat()->app_code->getUnlimit($parameter, [
                'width' => $size,
                'path' => $path,
            ]);
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $response->saveAs(ROOT_PATH . $savePath, $filename);
            }
        }
        return $this->run([
            'url' => DOMAIN_HTTP . ROOT_URL . '/' . $savePath . $filename,
            'file' => $savePath . $filename,
        ]);
    }

}
