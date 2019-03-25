<?php

/**
 * 站点设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\site\admin;


class ConfigAdmin extends \app\system\admin\SystemAdmin {

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return array(
            'info' => array(
                'name' => '站点配置',
                'description' => '配置站点基本信息',
            ),
        );
    }

    public function menu() {
        $menu = [
            [
                'name' => '站点信息',
                'url' => url('index'),
                'cur' => ACTION_NAME == 'index' ? 1 : 0,
            ],
            [
                'name' => '站点设置',
                'url' => url('config'),
                'cur' => ACTION_NAME == 'config' ? 1 : 0
            ],
            [
                'name' => '风格设置',
                'url' => url('tpl'),
                'cur' => ACTION_NAME == 'tpl' ? 1 : 0
            ],
            [
                'name' => '页面设置',
                'url' => url('page'),
                'cur' => ACTION_NAME == 'page' ? 1 : 0
            ],
        ];
        return $menu;
    }

    /**
     * 站点信息
     */
    public function index() {
        if (!isPost()) {
            $info = target('SiteConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->assign('configHtml', \dux\Dux::view()->fetch('app/site/view/admin/config/extend', [
                'hookConfig' => target('siteConfig')->configType('info'),
                'info' => $info
            ]));
            $this->systemDisplay();
        } else {
            if (target('SiteConfig')->saveInfo()) {
                $this->success('信息配置成功！');
            } else {
                $this->error('信息配置失败');
            }
        }
    }

    /**
     * 站点设置
     */
    public function config() {
        if (!isPost()) {
            $info = target('SiteConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->assign('configHtml', \dux\Dux::view()->fetch('app/site/view/admin/config/extend', [
                'hookConfig' => target('siteConfig')->configType('config'),
                'info' => $info
            ]));
            $this->systemDisplay();
        } else {
            if (target('SiteConfig')->saveInfo()) {
                $this->success('站点配置成功！');
            } else {
                $this->error('站点配置失败');
            }
        }
    }

    /**
     * 风格设置
     */
    public function tpl() {
        if (!isPost()) {
            $info = target('SiteConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->assign('configHtml', \dux\Dux::view()->fetch('app/site/view/admin/config/extend', [
                'hookConfig' => target('siteConfig')->configType('tpl'),
                'info' => $info
            ]));
            $this->systemDisplay();
        } else {
            if (target('SiteConfig')->saveInfo()) {
                $this->success('风格配置成功！');
            } else {
                $this->error('风格配置失败');
            }
        }
    }

    /**
     * 页面设置
     */
    public function page() {
        if (!isPost()) {
            $info = target('SiteConfig')->getConfig();
            $this->assign('info', $info);
            $this->assign('hookMenu', $this->menu());
            $this->assign('configHtml', \dux\Dux::view()->fetch('app/site/view/admin/config/extend', [
                'hookConfig' => target('siteConfig')->configType('page'),
                'info' => $info
            ]));
            $this->systemDisplay();
        } else {
            if (target('SiteConfig')->saveInfo()) {
                $this->success('页面配置成功！');
            } else {
                $this->error('页面配置失败');
            }
        }
    }



}