<?php

/**
 * 微信设置
 */
namespace app\wechat\model;

use app\system\model\SystemModel;

class WechatConfigModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'config_id',
        'into' => '',
        'out' => '',
    ];

    public function getConfig() {
        $list = $this->loadList();
        $data = array();
        foreach($list as $vo) {
            $data[$vo['name']] = $vo['content'];
        }
        return $data;
    }

    public function saveInfo() {
        $post = request('post');
        foreach ($post as $key => $value) {
            $where = array();
            $where['name'] = $key;
            $data = array();
            $data['content'] = html_in($value);
            if(!$this->data($data)->where($where)->update()){
                return false;
            }
        }
        return true;
    }


}