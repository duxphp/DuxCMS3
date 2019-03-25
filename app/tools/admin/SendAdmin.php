<?php

/**
 * 推送管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;

class SendAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'ToolsSend';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '推送管理',
                'description' => '管理系统推送消息队列',
            ],
            'fun' => [
                'index' => true,
                'status' => true,
                'del' => true
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'receive'
        ];
    }

    public function add() {
        if(!isPost()) {
            $this->assign('tplList', target('ToolsSendTpl')->loadList());
            $this->assign('classList', target('ToolsSendConfig')->classList());
            $this->systemDisplay();
        }else{
            $data = request('post');
            $param = array();
            if(!empty($data['param_key'])){
                foreach($data['param_key'] as $key => $vo){
                    $param[$vo] = $data['param_val'][$key];
                }
            }
            $data['param'] = $param;
            $status = target('tools/Tools', 'service')->sendMessage([
                'receive' => $data['receive'],
                'class' => $data['class'],
                'title' => $data['title'],
                'content' => $data['content'],
                'param' =>$data['param'],
            ]);
            if(!$status) {
                $this->error(target('tools/Tools', 'service')->getError());
            }
            $this->success('消息队列添加成功!', url('index'));

        }
    }

    public function info() {
        $id = request('get', 'id');
        if (empty($id)) {
            $this->error('参数不能为空！');
        }
        $model = target($this->_model);
        $info = $model->getInfo($id);
        if (!$info) {
            $this->error('信息不存在！');
        }
        $paramList = json_decode($info['param'], true);
        $this->assign('info', $info);
        $this->assign('paramList', $paramList);
        $this->assign('typeList', target('ToolsSendConfig')->typeList());
        $this->systemDisplay();
    }

}