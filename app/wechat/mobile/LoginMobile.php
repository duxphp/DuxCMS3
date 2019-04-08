<?php

/**
 * 微信登录
 */

namespace app\wechat\mobile;

class LoginMobile extends \app\base\controller\BaseController {

    protected $oauth = null;
    public $wechat = null;
    public $config = [];
    protected $noLogin = true;

    public function __construct() {
        parent::__construct();
        $target = target('wechat/Wechat', 'service');
        $target->init();
        $this->wechat = $target->wechat();
        $this->config = $target->config();
        $this->oauth = $this->wechat->oauth;
        $this->session = \dux\Dux::session();
    }

    /**
     * 登录跳转
     */
    public function index() {
        $_SESSION['target_url'] = $_SERVER['HTTP_REFERER'];
        if (empty($_SESSION['wechat_user'])) {
            $this->oauth->redirect()->send();
        } else {
            $this->login();
        }
    }


    /**
     * 回调授权
     */
    public function connect() {
        $user = $this->oauth->user();
        $original = $user->getOriginal();
        $wechatInfo = [
            'unionid' => $original['unionid'],
            'openid' => $user->getId(),
            'nickname' => $user->getName(),
            'avatar' => $user->getAvatar(),
        ];
        $_SESSION['wechat_user'] = $wechatInfo;
        $this->login();
    }


    /**
     * 回调授权
     */
    public function login() {
        $wechatUser = $_SESSION['wechat_user'];
        target('member/MemberUser')->beginTransaction();
        $data = target('member/Member', 'service')->oauthUser('wechat', $wechatUser['unionid'], $wechatUser['openid'], $wechatUser['nickname'], $wechatUser['avatar']);
        if (!$data) {
            $this->error(target('member/Member', 'service')->getError(), url('index/Index/index'));
        }
        target('member/MemberUser')->commit();
        $loginParams = http_build_query([
            'auth_uid' => $data['data']['uid'],
            'auth_token' => $data['data']['token'],
        ]);
        $config = target('site/SiteConfig')->getConfig();
        // 重写登陆成功后的跳转地址
        $targetUrl = empty($_SESSION['target_url']) ? $config['site_wap'] : $_SESSION['target_url'];
        if(strpos($targetUrl,'?') === false){
            if(strpos($targetUrl,'/') === false){
                $targetUrl .= '/?' . $loginParams;
            }else{
                $targetUrl .= '?' . $loginParams;
            }
        }else{
            $targetUrl .= '&' . $loginParams;
        }
        unset($_SESSION['wechat_user']);
        $this->redirect($targetUrl);

    }

}
