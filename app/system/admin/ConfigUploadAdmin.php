<?php

/**
 * 上传设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;


class ConfigUploadAdmin extends \app\system\admin\SystemAdmin {

    protected $_model = 'ConfigUpload';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '上传驱动',
                'description' => '配置系统上传驱动信息',
            ],
        ];
    }

    /**
     * 上传
     */
    public function index() {
        $this->assign('list', target('ConfigUpload')->loadList());
        $this->assign('name', target('ConfigUpload')->name());
        $this->systemDisplay();
    }

    /**
     * 配置
     */
    public function setting() {
        if (!isPost()) {
            $type = request('get', 'type');
            if (empty($type)) {
                $this->error('参数不能为空！');
            }
            $list = target('ConfigUpload')->loadList();
            $uploadConfig = $list[$type];
            $this->assign('uploadConfig', $uploadConfig);
            $this->assign('tip', target('ConfigUpload')->tip());
            $this->assign('name', target('ConfigUpload')->name());
            $this->systemDisplay();
        } else {
            $file = 'data/config/site/uploadDriver';
            $data = [
                $_POST['driver'] => $_POST,
            ];
            if (save_config($file, ['dux.upload_driver' => array_merge(\dux\Config::get('dux.upload_driver'), $data)])) {
                $this->success('上传驱动配置成功！');
            } else {
                $this->error('上传驱动配置失败');
            }
        }
    }

}
