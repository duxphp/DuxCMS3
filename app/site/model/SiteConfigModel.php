<?php

/**
 * 站点设置
 */
namespace app\site\model;

use app\system\model\SystemModel;

class SiteConfigModel extends SystemModel {

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
        $config = $this->getConfig();
        foreach ($post as $key => $value) {
            $where = array();
            $where['name'] = $key;
            $data = array();
            if(is_array($value)) {
                $data['content'] = serialize($value);
            }else{
                $data['content'] = html_in($value);
            }
            if(isset($config[$key])) {
                $status = $this->data($data)->where($where)->update();
            }else {
                $data['name'] = $key;
                $status = $this->data($data)->insert();
            }
            if(!$status){
                return false;
            }
        }
        return true;
    }

    public function configType($type) {
        $list = hook('service', 'Type', 'Config');
        $data = [];
        foreach ($list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        $newData = [];
        foreach ($data as $key => $vo) {
            if($vo['type'] == $type) {
                $newData[$key] = $vo;
            }
        }
        return $newData;
    }




}
