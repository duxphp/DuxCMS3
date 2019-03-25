<?php
namespace app\statis\service;
/**
 * 权限接口
 */
class PurviewService {
    /**
     * 获取模块权限
     */
    public function getSystemPurview() {
        return array(
            'SiteViews' => array(
                'name' => '访问统计',
                'auth' => array(
                    'index' => '信息',
                )
            ),
        );
    }


}
