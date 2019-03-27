<?php

/**
 * 文章内容
 */

namespace app\article\mobile;

class InfoMobile extends \app\base\mobile\SiteMobile {

    protected $_middle = 'article/Info';

    public function index() {
        $id = request('get', 'id', 0, 'intval');
        target($this->_middle, 'middle')->setParams([
            'article_id' => $id,
        ])->meta()->classInfo()->data()->export(function ($data) {
            $this->assign($data);
            $this->mobileDisplay($data['tpl']);
        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

}