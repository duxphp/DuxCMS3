<?php
namespace app\wechat\service;
/**
 * 权限接口
 */
class PurviewService {
    /**
     * 获取模块权限
     */
    public function getSystemPurview() {
        return array(
            'WechatConfig' => array(
                'name' => '微信设置',
                'auth' => array(
                    'index' => '设置',
                )
            ),
            'MenuConfig' => array(
                'name' => '菜单设置',
                'auth' => array(
                    'index' => '设置',
                )
            ),
            'MiniappConfig' => array(
                'name' => '小程序设置',
                'auth' => array(
                    'index' => '列表',
                )
            ),
            'AppConfig' => array(
                'name' => 'APP设置',
                'auth' => array(
                    'index' => '列表',
                )
            ),
        );
    }


}
