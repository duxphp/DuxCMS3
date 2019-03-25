<?php

/**
 * API设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;


class ConfigApiAdmin extends \app\system\admin\SystemAdmin {

    protected $_model = 'ConfigApi';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => 'API接口',
                'description' => '配置多种类API接口',
            ],
        ];
    }

    /**
     * 上传
     */
    public function index() {
        $this->assign('list', target($this->_model)->loadList());
        $this->systemDisplay();
    }

    public function add() {
        if (!isPost()) {
            $this->assign('apiList', target($this->_model)->getAllApi());
            $this->systemDisplay('info');
        } else {
            $file = 'data/config/use/api';
            $data = [
                $_POST['label'] => [
                    'name' => $_POST['name'],
                    'label' => $_POST['label'],
                    'key' => $_POST['key'],
                    'type' => $_POST['type'],
                    'rule' => json_encode($_POST['rule']),
                ],
            ];
            $apiConfig = \dux\Config::get('dux.api');

            if ($apiConfig[$_POST['label']]) {
                $this->error('标识不能重复！');
            }

            if (save_config($file, ['dux.api' => array_merge($apiConfig, $data)])) {
                $this->success('添加API成功！', url('index'));
            } else {
                $this->error('添加API失败');
            }
        }
    }

    public function edit() {
        $type = request('', 'label', 0, 'intval');
        if (!isPost()) {
            $list = target($this->_model)->loadList();
            $info = $list[$type];
            $info['rule'] = json_decode($info['rule']);
            $this->assign('info', $info);
            $this->assign('label', $type);
            $this->assign('apiList', target($this->_model)->getAllApi());
            $this->systemDisplay('info');
        } else {
            $file = 'data/config/use/api';
            $data = [
                [
                    'name' => $_POST['name'],
                    'key' => $_POST['key'],
                    'rule' => json_encode($_POST['rule']),
                ],
            ];
            $apiConfig = \dux\Config::get('dux.api');
            unset($apiConfig[$type]);
            if (save_config($file, ['dux.api' => array_merge($apiConfig, $data)])) {
                $this->success('修改API成功！', url('index'));
            } else {
                $this->error('修改API失败');
            }
        }
    }

    public function del() {
        $type = request('', 'id');
        $file = 'data/config/use/api';
        $apiConfig = \dux\Config::get('dux.api');
        unset($apiConfig[$type]);
        if (save_config($file, ['dux.api' => $apiConfig])) {
            $this->success('删除API成功！');
        } else {
            $this->error('删除API失败');
        }
    }

}
