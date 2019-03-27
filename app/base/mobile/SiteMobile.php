<?php

/**
 * 基础移动控制器
 */

namespace app\base\mobile;

class SiteMobile extends \app\base\controller\BaseController {

    protected $action = '';
    protected $siteConfig = [];

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
        if(!$this->siteConfig['page_mobile']) {
            $this->error404();
        }
        $this->action = request('get', 'action');
        if ($this->action) {
            $this->assign('action', $this->action);
        }
    }

    protected function mobileDisplay($tpl = '') {
        $this->assign('city', $this->city);
        $this->assign('sysPublic', $this->publicUrl);
        $this->siteConfig['page_theme'] = $this->siteConfig['page_theme'] . '_mobile';
        $moduleName = MODULE_NAME == 'Index' ? '' : '_' . MODULE_NAME;
        $actionName = ACTION_NAME == 'index' ? '' : '_' . ACTION_NAME;
        $tpl = 'theme/' . $this->siteConfig['page_theme'] . '/' . APP_NAME . strtolower($tpl ? '_' . $tpl : $moduleName . $actionName);
        $this->_getView()->addTag(function () {
            return [
                '/<!--#include\s*(.*)-->/' => [$this, 'includeFile'],
                '/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/)(.*?)[\"|\'](.*?)>/' => [$this, 'parseLoad'],
                '/__TPL__/' => ROOT_URL . '/theme/' . $this->siteConfig['page_theme'],
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
        $html = "<?php \$__Template->render(\"" . 'theme/' . $this->siteConfig['page_theme'] . "/" . $file . "\", [" . $params . "]); ?>";
        return $html;
    }

    public function parseLoad($var) {
        $file = $var[3] . $var[4];
        $url = 'theme' . '/' . $this->siteConfig['page_theme'];
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
