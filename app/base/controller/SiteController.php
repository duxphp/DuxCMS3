<?php

/**
 * 站点控制器
 */

namespace app\base\controller;

class SiteController extends \app\base\controller\BaseController {

    protected $siteConfig = [];
    protected $mobileJump = true;
    protected $city = [];

    public function __construct() {
        parent::__construct();
        target('system/Statistics', 'service')->incStats('web');
        $this->siteConfig = target('site/SiteConfig')->getConfig();
        if (method_exists($this, 'init')) {
            $this->init();
        }
        if (!$this->siteConfig['site_status']) {
            \dux\Dux::errorPage('站点维护', $this->siteConfig['site_error']);
        }
        $detect = new \dux\vendor\MobileDetect();
        $modules = \dux\Config::get('dux.module');
        if (LAYER_NAME == 'controller' && ($detect->isMobile() || $detect->isTablet()) && $this->siteConfig['page_mobile'] && $this->mobileJump) {
            $params = explode('/', $_SERVER["REQUEST_URI"], 3);
            if ($params[1] == $modules['controller']) {
                $url = $modules['mobile'] . '/' . $params[2];
            } else {
                $url = $modules['mobile'] . $_SERVER["REQUEST_URI"];
            }
            $this->redirect('/' . $url);
        }
    }

    protected function siteDisplay($tpl = '') {
        $this->assign('sysPublic', $this->publicUrl);
        $this->assign('site', $this->siteConfig);
        $moduleName = MODULE_NAME == 'Index' ? '' : '_' . MODULE_NAME;
        $actionName = ACTION_NAME == 'index' ? '' : '_' . ACTION_NAME;
        $tpl = 'theme/' . $this->siteConfig['page_theme'] . '/' . APP_NAME . strtolower($tpl ? '_' . $tpl : $moduleName . $actionName);
        $resDirs = 'images,img,css,js,style,' . $this->siteConfig['tpl_res'];
        $resDirs = html_clear($resDirs);
        $resDirsArray = explode(',', $resDirs);
        $resDirsArray = array_filter($resDirsArray);
        $resDirsTpl = implode('\/|', $resDirsArray) . '\/';
        $this->_getView()->addTag(function () use ($resDirsTpl) {
            return [
                '/<!--#include\s*(.*)-->/' => [$this, 'includeFile'],
                '/<(.*?)(src=|href=|value=|background=)[\"|\'](' . $resDirsTpl . ')(.*?)[\"|\'](.*?)>/' => [$this, 'parseLoad'],
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
            $html = '<div class="dux-pages"><a href="{prevUrl}"> <  上一页</a>';
            foreach ($pageData['pageList'] as $vo) {
                if ($vo == $pageData['current']) {
                    $html .= '<span class="current">' . $vo . '</span>';
                } else {
                    $html .= '<a href="' . $this->createPageUrl($vo, $params) . '">' . $vo . '</a>';
                }
            }
            $html .= '<a href="{nextUrl}">下一页  > </a></div>';
        }

        foreach ($pageData as $key => $vo) {
            $html = str_replace('{' . $key . '}', $vo, $html);
        }

        return $html;

    }

    protected function createPageUrl($page = 1, $params = []) {
        return $url = url(APP_NAME . '/' . MODULE_NAME . '/' . ACTION_NAME, array_merge((array) $params, ['page' => $page]));
    }

    protected function pageData($sumLimit, $pageLimit, $params = []) {
        $pageObj = new \dux\lib\Pagination($sumLimit, request('get', 'page', 1), $pageLimit);
        $pageData = $pageObj->build();
        $limit = [$pageData['offset'], $pageLimit];

        return [
            'html' => $this->htmlPage($pageData, $params),
            'limit' => $limit,
            'data' => $pageData,
        ];
    }

    protected function errorCallback($message = '', $code = 500, $url = '') {
        if ($code == 302 || $code == 301) {
            $this->redirect($url);
        } elseif ($code == 404) {
            $this->error404();
        }
        $this->error($message, $url, $code);
        exit;
    }

}