<?php

/**
 * 应用管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;

class ApplicationAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SystemApplication';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '应用管理',
                'description' => '管理系统中安装的扩展应用',
            ],
            'fun' => [
                'index' => true,
                'del' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'name'
        ];
    }

    public function index() {
        $list = target('system/SystemApplication')->loadList();
        foreach($list as $key => $vo) {
            if($vo['app.system']) {
                unset($list[$key]);
            }
        }
        $this->assign('list', $list);
        $this->systemDisplay();
    }

    public function status() {
        $app = request('post', 'app');
        $status = request('post', 'status');
        if(empty($app)) {
            $this->error('暂无获取到应用名!');
        }
        $model = target('system/SystemApplication');
        if(!$model->appStatus($app, $status)) {
            $model->getError();
        }
        $this->success('更改状态成功!');
    }

    public function install() {
        $app = request('post', 'app');
        if(empty($app)) {
            $this->error('暂无获取到应用名!');
        }
        $model = target('system/SystemApplication');
        if(!$model->appDetect($app)) {
            $model->getError();
        }
        if(!$model->appSql($app, 1)) {
            $model->getError();
        }
        if(!$model->appConfig($app, 1)) {
            $model->getError();
        }
        $this->success('应用安装成功!');
    }

    public function uninstall() {
        $app = request('post', 'app');
        if(empty($app)) {
            $this->error('暂无获取到应用名!');
        }
        $info = target('system/SystemApplication')->getInfo($app);
        if(empty($info)){
            $this->error('无法获取该应用配置！');
        }
        if(!$info['app.install']) {
            $this->error('该应用已经卸载!');
        }
        $model = target('system/SystemApplication');
        if(!$model->appSql($app, 0)) {
            $model->getError();
        }
        if(!$model->appConfig($app, 0)) {
            $model->getError();
        }
        $this->success('应用卸载成功,需要FTP删除对应目录!!');
    }


}