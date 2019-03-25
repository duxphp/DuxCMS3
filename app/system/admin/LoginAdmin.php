<?php
/**
 * 登录控制器
 */
namespace app\system\admin;

class LoginAdmin extends \app\base\controller\BaseController 
{

    /**
     * 登录页
     */
    public function index(){
        $vcode = new \dux\lib\Vcode(90, 37, 4);
        if(!isPost()){
            $this->assign('sysInfo', $this->sysInfo);
            $this->assign('valImage', $vcode->showImage());
            $this->display();
        }else{
            $userName = request('post', 'username');
            $passWord = request('post', 'password');
            $valCode = request('post', 'val_code');
            $valToken = request('post', 'val_token');
            $valTime = request('post', 'val_time');
            if (empty($userName) || empty($passWord)) {
                $this->error('用户名或密码未填写！');
            }
            if (!$vcode->check($valCode, $valToken, $valTime)) {
                $this->error('图片验证码输入不正确!');
            }
            if (target('system/SystemUser')->setLogin($userName, $passWord)) {
                $this->success('登录系统成功！', url('system/Index/index'));
            } else {
                $this->error(target('system/SystemUser')->getError());
            }
        }
    }

    public function imgCode() {
        $vcode = new \dux\lib\Vcode(90, 37, 4);
        $this->success($vcode->showImage());
    }

    /**
     * 退出登录
     */
    public function logout() {
        target('system/SystemUser')->logout();
        $this->redirect(url('index'));
    }
}