<?php
/**
 * 系统控制器
 */
namespace app\system\admin;

class SystemAdmin extends \app\base\controller\BaseController {

    protected $_view = null;
    protected $infoModule = [];
    protected $session = null;
    protected $userInfo = [];
    protected $sysTabs = [];

    public function __construct() {
        parent::__construct();
        $this->session = \dux\Dux::session();
        $this->initSystem();
    }

    /**
     * 初始化底层
     */
    private function initSystem() {
        $userInfo = target('system/SystemUser')->getLogin();
        if (!$userInfo) {
            $this->redirect(url('system/Login/index'));
        }
        $this->userInfo = $userInfo;
        define('USER_ID', $userInfo['user_id']);
        if (method_exists($this, '_infoModule')) {
            $this->infoModule = $this->_infoModule();
        }
        $this->checkPurview();
    }

    /**
     * 权限检测
     * @return bool
     */
    private function checkPurview() {
        if (empty($this->userInfo['purview'])) {
            return true;
        }
        $purviewInfo = target(APP_NAME . '/' . 'Purview', 'service')->getSystemPurview();
        if (empty($purviewInfo)) {
            return true;
        }
        $controller = !empty($purviewInfo[MODULE_NAME]) ? $purviewInfo[MODULE_NAME] : [];
        if (empty($controller['auth'])) {
            return true;
        }
        $action = $controller['auth'][ACTION_NAME];
        if (empty($action)) {
            return true;
        }
        if (!in_array(APP_NAME . '.' . MODULE_NAME . '.' . ACTION_NAME, (array)$this->userInfo['purview']) && $this->userInfo['role_id'] <> 1) {
            if(!isAjax()) {
                $this->systemError('您没有权限使用该功能！');
            }else{
                $this->error('您没有权限使用该功能！');
            }
        }
        return true;
    }

    protected function setTabs($data, $cur = 0) {
        if(!empty($data) && is_array($data)) {
            $data[$cur]['cur'] = true;
        }
        $this->sysTabs = $data;
    }

    /**
     * 系统错误输出
     * @param $msg
     */
    protected function systemError($msg) {
        $this->assign('msg', $msg);
        $this->systemDisplay('app/system/view/' . LAYER_NAME . '/common/error.html', false);
    }

    /**
     * 系统弹出错误输出
     * @param $msg
     */
    protected function systemDialogError($msg) {
        $this->assign('msg', $msg);
        $this->dialogDisplay('app/system/view/' . LAYER_NAME . '/common/error.html', false);
    }

    /**
     * 系统模板输出
     * @param string $tpl
     */
    protected function systemDisplay($tpl = '', $autoDir = true) {
        $module = !empty($url) ? $url : APP_NAME .'/' . MODULE_NAME;
        $sysMenu = target('system/Menu')->loadList(['module' => $module, 'auth' => $this->userInfo['purview']]);
        $this->assign('sysNav', $sysMenu['nav']);
        $this->assign('sysCrumb', $sysMenu['crumb']);
        $this->assign('sysAside', $sysMenu['aside']);
        $this->assign('infoModule', $this->infoModule);
        $this->assign('sysInfo', $this->sysInfo);
        $this->assign('sysPublic', $this->publicUrl);
        $this->assign('sysUserInfo', $this->userInfo);
        $this->assign('sysTabs', $this->sysTabs);
        $this->layout = 'app/system/view/' . LAYER_NAME . '/common/common';
        if (!empty($tpl) && $autoDir) {
            $tpl = 'app/' . APP_NAME . '/view/' . LAYER_NAME . '/' . strtolower(MODULE_NAME) . '/' . strtolower($tpl);
        }
        $this->display($tpl);
    }

    /**
     * UI模板输出
     * @param $data
     */
    protected function uiDisplay($data) {
        $this->assign($data['data']);
        $this->systemDisplay($data['tpl']);
    }

    /**
     * 弹出模板输出
     */
    protected function dialogDisplay($tpl = '', $autoDir = true) {
        $this->assign('sysInfo', $this->sysInfo);
        $this->assign('sysPublic', $this->publicUrl);
        $this->assign('sysUserInfo', $this->userInfo);
        $this->layout = 'app/system/view/' . LAYER_NAME . '/common/dialog';
        if (!empty($tpl) && $autoDir) {
            $tpl = 'app/' . APP_NAME . '/view/' . LAYER_NAME . '/' . strtolower(MODULE_NAME) . '/' . strtolower($tpl);
        }
        $this->display($tpl);
    }


}