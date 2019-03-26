<?php

/**
 * 文章栏目
 */

namespace app\page\mobile;

class IndexMobile extends \app\base\mobile\SiteMobile {

    protected $_middle = 'page/Info';

    public function index() {
        $id = request('get', 'id', 0, 'intval');
        target($this->_middle, 'middle')->setParams([
            'id' => $id,
        ])->meta()->data()->export(function ($data) {
            $this->assign($data);
            $this->mobileDisplay($data['tpl']);
        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

}