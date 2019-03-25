<?php

/**
 * 系统设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;


class ConfigAdmin extends \app\system\admin\SystemAdmin {

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '系统配置',
                'description' => '配置系统基本信息与设置',
            ],
        ];
    }

    public function menu() {
        $menu = [
            [
                'name' => '系统信息',
                'url' => url('index'),
                'cur' => ACTION_NAME == 'index' ? 1 : 0,
            ],
            [
                'name' => '性能设置',
                'url' => url('user'),
                'cur' => ACTION_NAME == 'user' ? 1 : 0,
            ],
            [
                'name' => '上传设置',
                'url' => url('upload'),
                'cur' => ACTION_NAME == 'upload' ? 1 : 0,
            ],
        ];
        return $menu;
    }

    /**
     * 系统信息
     */
    public function index() {
        $file = 'data/config/use/info';
        if (!isPost()) {
            $config = load_config($file);
            $this->assign('info', $config['dux.use_info']);
            $this->assign('hookMenu', $this->menu());
            $this->systemDisplay();
        } else {
            if (save_config($file, ['dux.use_info' => $_POST])) {
                $this->success('系统信息配置成功！');
            } else {
                $this->error('系统信息配置失败');
            }
        }
    }

    /**
     * 性能设置
     */
    public function user() {
        $file = 'data/config/use/use';
        if (!isPost()) {
            $config = load_config($file);
            $this->assign('info', $config['dux.use']);
            $this->assign('cacheList', \dux\Config::get('dux.cache'));
            $this->assign('storageList', \dux\Config::get('dux.storage'));
            $this->assign('hookMenu', $this->menu());
            $this->systemDisplay();
        } else {
            if (save_config($file, ['dux.use' => $_POST])) {
                $this->success('性能配置成功！');
            } else {
                $this->error('性能配置失败');
            }
        }
    }


    /**
     * 上传设置
     */
    public function upload() {
        $file = 'data/config/use/upload';
        if (!isPost()) {
            $config = load_config($file);
            $this->assign('info', $config['dux.use_upload']);
            $this->assign('driverList', \dux\Config::get('dux.upload_driver'));
            $this->assign('imageList', \dux\Config::get('dux.image_driver'));
            $this->assign('hookMenu', $this->menu());
            $this->systemDisplay();
        } else {
            if (save_config($file, ['dux.use_upload' => $_POST])) {
                $this->success('上传配置成功！');
            } else {
                $this->error('上传配置失败');
            }
        }
    }

}
