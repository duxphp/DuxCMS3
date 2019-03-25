<?php

/**
 * 小程序二维码生成
 */

namespace app\wechat\middle;

class MiniappMiddle extends \app\base\middle\BaseMiddle {

    public function wechat() {
        return target('wechat/Miniapp', 'service')->init([], $this->params['app']);
    }

    //openid转换
    public function getUserInfo($code, $iv = '', $encryptedData = '') {
        try {
            $data = $this->wechat()->auth->session($code);
            if(!empty($iv) && !empty($encryptedData) && empty($data['unionid'])) {
                $userInfo = $this->wechat()->encryptor->decryptData($data['session_key'], $iv, $encryptedData);
                $data['unionid'] = $userInfo['unionId'];
            }
            return $this->run($data, 'ok');
        }catch (\Exception $exception) {
            return $this->stop($exception->getMessage());
        }
    }

    //永久二维码
    protected function perpetual($path, $size = 430) {
        $savePath = 'upload/qrcode/mini_perpetual/';
        $filename = md5('perpetual' . $path . $size) . '.png';
        if (!is_file(ROOT_PATH . $savePath . $filename)) {
            $response = $this->wechat()->app_code->getQrCode($path, $size);
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
        $savePath = 'upload/qrcode/mini_tmp/';
        $filename = md5('tmp' . $path . $parameter . $size) . '.png';
        if (!is_file(ROOT_PATH . $savePath . $filename)) {
            $response = $this->wechat()->app_code->getUnlimit($parameter, [
                'width' => $size,
                'page' => $path,
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
