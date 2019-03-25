<?php

/**
 * 系统首页
 */

namespace app\index\controller;

class IndexController extends \app\base\controller\SiteController {

    /**
     * 首页
     */
    public function index() {
        target('index/index', 'middle')->meta()->export(function ($data) {
            $this->assign($data);
            $this->siteDisplay();
        });
    }

}