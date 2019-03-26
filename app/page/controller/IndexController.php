<?php

/**
 * 栏目
 */

namespace app\page\controller;

class IndexController extends \app\base\controller\SiteController {

    protected $_middle = 'page/Info';

    public function index() {
        $id = request('get', 'id', 0, 'intval');
        target($this->_middle, 'middle')->setParams([
            'id' => $id,
        ])->meta()->data()->export(function ($data) {
            $this->assign($data);
            $this->siteDisplay($data['tpl']);
        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

}