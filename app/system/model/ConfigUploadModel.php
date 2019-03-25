<?php

/**
 * 上传设置
 */
namespace app\system\model;

class ConfigUploadModel {

    public function loadList() {
        $list = \dux\Config::get('dux.upload_driver');
        return $list;
    }

    public function name() {
        return [
            'local' => '本地',
            'qiniu' => '七牛云存储',
            'oss' => '阿里云OSS',
        ];
    }

    public function tip() {
        return [
            'access_id' => '账户ID',
            'access_key' => '云存储公钥',
            'secret_key' => '云存储私钥',
            'bucket' => '存储空间名',
            'domain' => '资源访问域名',
            'url' => '资源上传地址',
        ];

    }

}