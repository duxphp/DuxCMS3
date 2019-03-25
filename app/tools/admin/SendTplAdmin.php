<?php

/**
 * 模板管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;

class SendTplAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'ToolsSendTpl';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '模板管理',
                'description' => '管理消息发送使用模板',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'title'
        ];
    }

    public function getTpl() {
        $this->success(target($this->_model)->getInfo(request('post', 'id')));
    }

}