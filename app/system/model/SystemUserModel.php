<?php

/**
 * 用户管理
 */
namespace app\system\model;

use app\system\model\SystemModel;

class SystemUserModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'user_id',
        'validate' => [
            'username' => [
                'len' => ['4,20', '用户名称只能为3~20个字符', 'must', 'all'],
                'unique' => ['', '已存在相同的用户名', 'must', 'all'],
            ],
            'nickname' => [
                'len' => ['2,20', '昵称只能为2~20个字符', 'must', 'all'],
                'unique' => ['', '已存在相同的用户名', 'must', 'all'],
            ],
            'password' => [
                'len' => ['3,50', '请输入3~50位密码', 'must', 'add'],
            ]
        ],
        'format' => [
            'password' => [
                'ignore' => ['', 'edit'],
            ],
            'reg_time' => [
                'function' => ['time', 'add'],
            ]
        ],
        'into' => '',
        'out' => '',
    ];

    protected function _saveBefore($data) {
        if(!empty($data['role_ext'])) {
            $data['role_ext'] = implode(',', $data['role_ext']);
        }
        if($data['password']) {
            $data['password'] = md5($data['password']);
        }
        return $data;
    }

    protected function base($where) {
        return $this->table('system_user(A)')
            ->join('system_role(B)', ['B.role_id', 'A.role_id'])
            ->field(['A.*', 'B.name(role_name)'])
            ->where((array)$where);
    }

    public function loadList($where = array(), $limit = 0, $order = '') {
        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        return $list;
    }

    public function countList($where = array()) {
        return $this->base($where)->count();
    }

    public function getWhereInfo($where) {
        return $this->base($where)->find();
    }

    /**
     * 设置登录
     * @param $userName
     * @param $passWord
     * @return bool
     * @throws \Exception
     */
    public function setLogin($userName, $passWord) {
        $map = array();
        $map['username'] = $userName;
        $userInfo = $this->getWhereInfo($map);
        if (empty($userInfo)) {
            $this->error = '该用户不存在!';
            return false;
        }
        if (!$userInfo['status']) {
            $this->error = '该用户已被禁止登录!';
            return false;
        }
        if ($userInfo['password'] <> md5($passWord)) {
            $this->error = '密码输入错误,请重新输入!';
            return false;
        }
        $data = array(
            'user_id' => $userInfo['user_id'],
            'login_time' => time(),
            'login_ip' => \dux\lib\Client::getUserIp(),
        );
        if (!$this->edit($data)) {
            $this->error = '登陆失败,请稍后再试!';
            return false;
        }
        $loginInfo = array(
            'user_id' => $userInfo['user_id'],
            'password' => md5($passWord)
        );
        \dux\Dux::session()->set('system_user', $loginInfo);
        \dux\Dux::session()->set('system_user_sign', data_sign($loginInfo));
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout() {
        \dux\Dux::session()->clear();
    }

    /**
     * 获取登陆信息
     */
    public function getLogin() {
        //获取登陆信息
        $loginInfo = \dux\Dux::session()->get('system_user');
        if(empty($loginInfo)) {
            return false;
        }
        if(!data_sign_has($loginInfo, \dux\Dux::session()->get('system_user_sign'))) {
            return false;
        }
        $userInfo = $this->getWhereInfo(['user_id' => $loginInfo['user_id']]);
        if(empty($userInfo)) {
            return false;
        }
        if (empty($userInfo['avatar'])) {
            $userInfo['avatar'] = ROOT_URL . '/public/system/images/avatar.png';
        }
        //获取用户权限
        $jobIds = $userInfo['role_id'];
        if(!empty($userInfo['role_ext'])) {
            $jobIds .= ',' . $userInfo['role_ext'];
        }
        $jobExtName = array();
        $purview = array();
        $where = array();
        $where['_sql'] = 'role_id in (' . $jobIds . ')';
        $jobList = target('system/SystemRole')->loadList($where);

        foreach ($jobList as $vo) {
            $purview = array_merge($purview, unserialize($vo['purview']));
            $jobExtName[] = $vo['name'];
        }
        $userInfo['role_names'] = $jobExtName;
        $userInfo['purview'] = $purview;
        return $userInfo;
    }



}