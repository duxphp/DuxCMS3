<?php

/**
 * 基础移动控制器
 */

namespace app\base\mobile;

class SiteMobile extends \app\base\controller\BaseController {

    protected $action = '';
    protected $siteConfig = [];
    protected $city = [];

    protected $userInfo = [];
    protected $noLogin = false;
    protected $take = [];

    public function __construct() {
        parent::__construct();
        target('system/Statistics', 'service')->incStats('mobile');
        $this->siteConfig = target('site/SiteConfig')->getConfig();
        if (method_exists($this, 'init')) {
            $this->init();
        }
        if (!$this->siteConfig['site_status']) {
            \dux\Dux::errorPage('站点维护', $this->siteConfig['site_error']);
        }
        $this->citySwitch();
        $this->initLogin();
        if (isWechat()) {
            @$this->wechatInit();
        }
        $this->action = request('get', 'action');
        if ($this->action) {
            $this->assign('action', $this->action);
        }
        $this->getTake();
        if (isWechat()) {
            @$this->initShare();
        }
    }

    private function citySwitch() {
        $getCity = request('get', 'city');
        $time = 31536000;
        if ($getCity) {
            $city = $getCity;
        } else {
            $city = \dux\Dux::cookie()->get('city');
        }
        if (empty($city)) {
            $str = \dux\lib\Http::doGet('http://ip.taobao.com/service/getIpInfo.php?ip=' . \dux\lib\Client::getUserIp());
            $data = json_decode($str, true);
            $city = $data['data']['city'];
        }
        $cityInfo = target('city/City')->getWhereInfo(['label[~]' => $city]);
        if (empty($cityInfo)) {
            $cityInfo = target('city/City')->getWhereInfo(['status' => 1]);
        }
        \dux\Dux::cookie()->set('city', $cityInfo['label'], $time);
        if (!defined('CITY_ID')) define('CITY_ID', $cityInfo['city_id']);
        $this->city = $cityInfo;

        target('statis/Views', 'service')->statis([
            'city_id' => CITY_ID,
            'species' => 'city',
        ]);
    }

    /**
     * 初始化登录状态
     */
    protected function initLogin() {
        $userInfo = $this->getLogin();
        if (!$userInfo) {
            $uid = request('get', 'uid', 0, 'intval');
            $token = request('get', 'token');
            if ($uid && $token) {
                $login = [
                    'uid' => $uid,
                    'token' => $token,
                ];
                \dux\Dux::cookie()->set('user_login', $login);
                if (!isAjax()) {
                    $this->redirect(url('', array_merge(request('get'), ['uid' => '', 'token' => ''])));
                } else {
                    $this->error('登录成功，请刷新页面!', url('', ['uid' => '', 'token' => '']));
                }
            }
        }
        if (!$this->noLogin && !$userInfo) {
            if (!isAjax()) {
                $this->redirect(url('member/Login/index', ['action' => URL]));
            } else {
                $this->error('您尚未登录,请先登录进行操作!', url('member/Login/index'), 401);
            }
        }
        $this->userInfo = $userInfo;
        define('USER_ID', $userInfo['user_id']);
        $this->assign('userInfo', $this->userInfo);

        target('statis/Views', 'service')->statis([
            'city_id' => CITY_ID,
            'user_id' => USER_ID,
            'species' => 'city_user',
        ]);

    }

    private function wechatInit() {
        //微信基本配置
        $target = target('wechat/Wechat', 'service')->init();
        $jsConfig = $target->jssdk->buildConfig(['openLocation', 'getLocation', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone'], false);
        //微信分享配置
        $share = [];
        $share['title'] = $this->city['share_title'];
        $share['desc'] = $this->city['share_desc'];
        $share['image'] = $this->city['share_image'];
        if (empty($share['image'])) {
            $share['image'] = DOMAIN_HTTP . '/theme/default_mobile/images/logo.jpg';
        }
        $saleUser = target('base/Base')->table('group_recommend(A)')
            ->join('group_user(B)', ['A.user_id', 'B.user_id'])
            ->where([
                'A.user_id' => $this->userInfo['user_id'],
                'B.status' => 1,
            ])->find();
        if ($saleUser) {
            $saleCode = $saleUser['code'];
        } else {
            $saleCode = request('sale_code');
        }
        $url = DOMAIN;
        if (strpos($url, '?') === false) {
            $url = $url . '?city=' . $this->city['label'] . '&sale_code=' . $saleCode;
        } else {
            $url = $url . '&city=' . $this->city['label'] . '&sale_code=' . $saleCode;
        }
        $share['url'] = $url;
        $jsContent = "<script src=\"https://res.wx.qq.com/open/js/jweixin-1.0.0.js\" type=\"text/javascript\" charset=\"utf-8\"></script><script type=\"text/javascript\" charset=\"utf-8\">wx.config({$jsConfig});wx.ready(function () {var share = {title: '{$share['title']}',desc: '{$share['desc']}',link: '{$share['url']}',imgUrl: '{$share['image']}' };wx.onMenuShareTimeline(share);wx.onMenuShareAppMessage(share);wx.onMenuShareQQ(share);wx.onMenuShareWeibo(share);wx.onMenuShareQZone(share);});</script>";
        $this->assign('wechatJs', $jsContent);


    }

    private function initShare() {
        $share = [];
        $share['title'] = $this->city['share_title'];
        $share['desc'] = $this->city['share_desc'];
        $share['image'] = $this->city['share_image'];
        if (empty($share['image'])) {
            $share['image'] = DOMAIN_HTTP . '/theme/default_mobile/images/logo.jpg';
        }
        $saleUser = target('group/GroupRecommend')->getWhereInfo([
            'A.user_id' => $this->userInfo['user_id'],
        ]);
        $saleGroup = target('group/GroupUser')->getWhereInfo([
            'A.user_id' => $this->userInfo['user_id'],
            'A.status' => 1,
        ]);
        if ($saleUser && $saleGroup) {
            $saleCode = $saleUser['code'];
        } else {
            $saleCode = request('sale_code');
        }
        $url = DOMAIN;
        if (strpos($url, '?') === false) {
            $url = $url . '?city=' . $this->city['label'] . '&sale_code=' . $saleCode;
        } else {
            $url = $url . '&city=' . $this->city['label'] . '&sale_code=' . $saleCode;
        }
        $share['url'] = $url;

        $jsConfig = '';
        $target = target('wechat/Wechat', 'service');
        $target->init();
        $jsConfig = $target->wechat()->jssdk->buildConfig(['openLocation', 'getLocation', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone'], false);

        $this->assign('shareInfo', $share);
        $this->assign('shareConfigJs', $jsConfig);
    }

    protected function getTake() {
        $data = target('group/Take', 'middle')->setParams([
            'lat' => \dux\Dux::cookie()->get('pos_lat'),
            'lng' => \dux\Dux::cookie()->get('pos_lng'),
            'take_id' => \dux\Dux::cookie()->get('take'),
            'sale_code' => request('', 'sale_code'),
            'cityInfo' => $this->city,
            'user_id' => $this->userInfo['user_id'],
        ])->data()->export(function ($data) {
            return $data;
        });
        $takeInfo = $data['takeList'][0];
        $this->take = $takeInfo;
        $this->assign('take', $takeInfo);
    }


    /**
     * 获取登录信息
     * @return bool
     */
    protected function getLogin() {
        $login = \dux\Dux::cookie()->get('user_login');
        if (empty($login)) {
            return false;
        }
        if (!target('member/MemberUser')->checkUser($login['uid'], $login['token'])) {
            return false;
        }
        $info = target('member/MemberUser')->getUser($login['uid'], $this->city['city_id']);
        if (!$info) {
            $this->error(target('member/MemberUser')->getError());
        }
        target('member/MemberUserLogin')->increase($login['uid'], $this->city['city_id']);
        return $info;
    }

    protected function mobileDisplay($tpl = '') {
        $this->assign('city', $this->city);
        $this->assign('sysPublic', $this->publicUrl);
        $this->siteConfig['tpl_name'] = $this->siteConfig['tpl_name'] . '_mobile';
        $moduleName = MODULE_NAME == 'Index' ? '' : '_' . MODULE_NAME;
        $actionName = ACTION_NAME == 'index' ? '' : '_' . ACTION_NAME;
        $tpl = 'theme/' . $this->siteConfig['tpl_name'] . '/' . APP_NAME . strtolower($tpl ? '_' . $tpl : $moduleName . $actionName);
        $this->_getView()->addTag(function () {
            return [
                '/<!--#include\s*(.*)-->/' => [$this, 'includeFile'],
                '/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/)(.*?)[\"|\'](.*?)>/' => [$this, 'parseLoad'],
                '/__TPL__/' => ROOT_URL . '/theme/' . $this->siteConfig['tpl_name'],
                '/__ROOT__/' => ROOT_URL,
            ];
        });
        $this->display($tpl);
        exit;
    }

    public function includeFile($label) {
        $reg = '/(file|data)\=[\"|\'](.*)[\"|\']/U';
        preg_match_all($reg, $label[1], $vars);
        if (empty($vars[1])) {
            return false;
        }
        $file = '';
        $params = [];
        foreach ($vars[1] as $key => $vo) {
            if ($vo == 'file') {
                $file = trim($vars[2][$key]);
                $file = preg_replace('/\.(html|htm)/', '', $file);
            }
            if ($vo == 'data') {
                $data = explode(',', $vars[2][$key]);
                foreach ($data as $k => $v) {
                    $raw = explode('=', $v, 2);
                    $params[] = '"' . $raw[0] . '"=>"' . $raw[1] . '"';
                }
            }
        }
        $params = implode(',', $params);
        $html = "<?php \$__Template->render(\"" . 'theme/' . $this->siteConfig['tpl_name'] . "/" . $file . "\", [" . $params . "]); ?>";
        return $html;
    }

    public function parseLoad($var) {
        $file = $var[3] . $var[4];
        $url = 'theme' . '/' . $this->siteConfig['tpl_name'];
        if (substr($url, 0, 1) == '.') {
            $url = substr($url, 1);
        }
        $url = str_replace('\\', '/', $url);
        $url = ROOT_URL . '/' . $url . '/' . $file;
        $html = '<' . $var[1] . $var[2] . '"' . $url . '"' . $var[5] . '>';
        return $html;
    }

    protected function htmlPage($pageData, $params = []) {
        if (empty($pageData)) {
            return '';
        }
        $pageData['prevUrl'] = $this->createPageUrl($pageData['prev'], $params);
        $pageData['nextUrl'] = $this->createPageUrl($pageData['next'], $params);
        if (method_exists($this, '_pageHtml')) {
            $html = $this->_pageHtml($pageData, $params);
        } else {
            $html = '<div class="prev"><a href="{prevUrl}">上一页</a></div><div class="number"><span class="cur">第{current}页</span> <i class="fa fa-angle-down"></i><select onchange="window.location.href = this.options[this.selectedIndex].value;">';
            foreach ($pageData['pageList'] as $vo) {
                if ($vo == $pageData['current']) {
                    $html .= '<option value="' . $this->createPageUrl($vo, $params) . '" selected>第' . $vo . '页</option>';
                } else {
                    $html .= '<option value="' . $this->createPageUrl($vo, $params) . '">第' . $vo . '页</option>';
                }
            }
            $html .= '</select></div><div class="next"><a href="{nextUrl}">下一页</a></div>';
        }
        foreach ($pageData as $key => $vo) {
            $html = str_replace('{' . $key . '}', $vo, $html);
        }
        return $html;

    }

    protected function createPageUrl($page = 1, $params = []) {
        return $url = url(APP_NAME . '/' . MODULE_NAME . '/' . ACTION_NAME, array_merge((array)$params, ['page' => $page]));
    }


    protected function errorCallback($message = '', $code = 500, $url = '') {
        if ($code == 302 || $code == 301) {
            $this->redirect($url);
        } else if ($code == 404) {
            $this->error404();
        }
        $this->error($message, $url, $code);
        exit;
    }


}
