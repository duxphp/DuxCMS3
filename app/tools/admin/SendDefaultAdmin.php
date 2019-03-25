<?php

/**
 * 默认推送
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;


class SendDefaultAdmin extends \app\system\admin\SystemAdmin {

    protected $_model = 'ToolsSendDefault';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return array(
            'info' => array(
                'name' => '默认设置',
                'description' => '设置推送种类默认类型',
            ),
        );
    }

    /**
     * 默认设置
     */
    public function index() {
        if (!isPost()) {
            $this->assign('list', target('ToolsSendConfig')->typeList());
            $this->assign('classList', target('ToolsSendConfig')->classList());

            $this->systemDisplay();
        } else {
            $post = request('post');
            foreach ($post as $key => $vo) {
                $info = target($this->_model)->getWhereInfo([
                    'class' => $key
                ]);
                $data = array();
                $data['class'] = $key;
                $data['type'] = $vo;
                $data['tpl'] = $post[$key . '_tpl'];
                if (!empty($info)) {
                    $data['default_id'] = $info['default_id'];
                    $type = 'edit';
                } else {
                    $type = 'add';
                }
                if (!target($this->_model)->saveData($type, $data)) {
                    $this->error(target($this->_model)->getError());
                }
            }
            $this->success('保存成功！');
        }
    }


}