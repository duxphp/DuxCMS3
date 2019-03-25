<?php

/**
 * 用户管理
 */
namespace app\system\model;

use app\system\model\SystemModel;

class SystemRoleModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'role_id',
        'validate' => [
            'name' => [
                'len' => ['1, 20', '角色名称只能为英文数字或下划线', 'must', 'all'],
            ],
        ],
        'format' => [
            'name' => [
                'function' => ['htmlspecialchars', 'all'],
            ],
            'description' => [
                'function' => ['htmlspecialchars', 'all'],
            ],
        ],
        'into' => '',
        'out' => '',
    ];

    protected function _saveBefore($data) {
        $data['purview'] = serialize($data['purview']);
        return $data;
    }

    public function getAllPurview() {
        $list = hook('service', 'Purview', 'System');
        $appList = target('system/SystemApplication')->loadList();
        foreach ($list as $key => $vo) {
            $appInfo = $appList[$key];
            $list[$key] = array();
            $list[$key]['auth'] = $vo;
            $list[$key]['name'] = $appInfo['app.name'];
        }
        return $list;

    }

}