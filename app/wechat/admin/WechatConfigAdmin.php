<?php

/**
 * 微信设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class WechatConfigAdmin extends \app\system\admin\SystemAdmin {

    /**
     * 模块信息
     */
    protected function _infoModule(){
        return array(
            'info' => array(
                'name' => '微信设置',
                'description' => '设置微信配置信息',
            ),
        );
    }

    public function menu() {
        $menu = [
            [
                'name' => '基本配置',
                'url' => url('index'),
                'cur' => ACTION_NAME == 'index' ? 1 : 0,
            ],
            [
                'name' => '消息设置',
                'url' => url('message'),
                'cur' => ACTION_NAME == 'message' ? 1 : 0,
            ],
            [
                'name' => '关注设置',
                'url' => url('focus'),
                'cur' => ACTION_NAME == 'focus' ? 1 : 0,
            ],
        ];
        return $menu;
    }

    public function index() {
        if(!isPost()) {
            $info = target('WechatConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->systemDisplay();
        }else{
            if(target('WechatConfig')->saveInfo()){
                $this->success('微信配置成功！');
            }else{
                $this->error('微信配置失败');
            }
        }
    }

    public function message() {
        if(!isPost()) {
            $info = target('WechatConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->systemDisplay();
        }else{
            if(target('WechatConfig')->saveInfo()){
                $this->success('微信配置成功！');
            }else{
                $this->error('微信配置失败');
            }
        }
    }

    public function focus() {
        if(!isPost()) {
            $info = target('WechatConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->systemDisplay();
        }else{
            if(target('WechatConfig')->saveInfo()){
                $this->success('微信配置成功！');
            }else{
                $this->error('微信配置失败');
            }
        }
    }


}