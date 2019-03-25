<?php

/**
 * 用户管理
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;

class UserAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SystemUser';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '用户管理',
                'description' => '管理系统用户管理员',
                'url' => url('system/User/index'),
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
                'status' => true,
            ]
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'username'
        ];
    }

    protected function _addAssign() {
        return array(
            'roleList' =>target('system/SystemRole')->loadList()
        );
    }

    protected function _editAssign($info) {
        return array(
            'roleList' => target('system/SystemRole')->loadList(),
            'roleArray' => explode(',', $info['role_ext'])
        );
    }

    protected function _delBefore($id) {
        if ($id == 1) {
            $this->error('保留用户无法删除！');
        }
    }

}