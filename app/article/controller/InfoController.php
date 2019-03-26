<?php

/**
 * 文章内容
 */

namespace app\article\controller;

class InfoController extends \app\base\controller\SiteController {

    protected $_middle = 'article/Info';

    public function index() {
        $id = request('get', 'id', 0, 'intval');
        target($this->_middle, 'middle')->setParams([
            'article_id' => $id,
        ])->meta()->classInfo()->data()->export(function ($data) {
            $this->assign($data);
            $this->siteDisplay($data['tpl']);
        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

}