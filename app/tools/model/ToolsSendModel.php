<?php

/**
 * 推送队列
 */
namespace app\tools\model;

use app\system\model\SystemModel;

class ToolsSendModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'send_id'
    ];

    public function loadList($where = array(), $limit = 0, $order = '') {
        $typeList = target('tools/ToolsSendConfig')->typeList();
        $list = $this->where($where)->limit($limit)->order('send_id desc')->select();
        foreach ($list as $key => $vo) {
            $list[$key]['type_name'] = $typeList[$vo['type']]['name'];
        }
        return $list;
    }

    public function send($data, $hasId) {
        $typeList = target('tools/ToolsSendConfig')->typeList();
        $typeInfo = $typeList[$data['type']];
        if (empty($typeInfo)) {
            $this->error = '推送类型' . $data['type'] . '不存在!';
            return false;
        }

        $sendData = array();
        $sendData['receive'] = $data['receive'];
        $sendData['title'] = $data['title'];
        $sendData['content'] = $data['content'];
        $sendData['param'] = json_decode($data['param'], true);
        $sendData['user_info'] = [];

        if($data['user_status']) {
            $sendData['user_info'] = target('member/MemberUser')->getInfo($data['receive']);
        }

        if (!empty($sendData['param'])) {
            foreach ($sendData['param'] as $key => $vo) {
                $sendData['content'] = str_replace('{' . $key . '}', $vo, $sendData['content']);
            }
        }
        if(target($typeInfo['target'], 'send')->send($data)){
            $this->complete($hasId, '推送成功！', true);
            return true;
        }else{
            $this->complete($hasId, target($typeInfo['target'], 'send')->getError(), false);
            $this->error = target($typeInfo['target'], 'send')->getError();
            return false;
        }
    }

    protected function complete($sendId, $remark = '未知', $status = true) {
        $data = array();
        $data['send_id'] = $sendId;
        if($status) {
            $data['status'] = 1;
        }else{
            $data['status'] = 2;
        }
        $data['remark'] = $remark;
        $data['stop_time'] = time();
        return target('tools/ToolsSend')->edit($data);
    }


}