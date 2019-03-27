<?php

/**
 * 系统首页
 */

namespace app\index\mobile;


class IndexMobile extends \app\base\mobile\SiteMobile {

    /**
     * 首页
     */
    public function index() {
        target('index/index', 'middle')->meta()->export(function ($data) {
            $this->assign($data);
            $this->mobileDisplay();
        });
    }

}