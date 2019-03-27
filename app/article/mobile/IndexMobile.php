<?php

/**
 * 文章栏目
 */

namespace app\article\mobile;

class IndexMobile extends \app\base\mobile\SiteMobile {

    protected $_middle = 'article/List';

    /**
     * 首页
     */
    public function index() {
        $classId = request('get', 'id', 0, 'intval');
        $pageLimit = request('get', 'limit', 0, 'intval');

        $urlParams = [
            'id' => $classId,
            'limit' => $pageLimit,
        ];

        target($this->_middle, 'middle')->setParams([
            'classId' => $classId,
            'limit' => $pageLimit,
        ])->meta()->classInfo()->data()->export(function ($data) use ($urlParams) {
            $this->assign($data);
            $this->assign('urlParams', $urlParams);
            $this->assign('page', $this->htmlPage($data['pageData']['raw'], $urlParams));
            $this->mobileDisplay($data['tpl']);
        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

    public function ajax() {
        $classId = request('get', 'id', 0, 'intval');
        $pageLimit = request('get', 'limit', 0, 'intval');

        target($this->_middle, 'middle')->setParams([
            'classId' => $classId,
            'limit' => $pageLimit,
        ])->data()->export(function ($data) {
            if(!empty($data['pageList'])) {
                $this->success([
                    'data' => $data['pageList'],
                    'page' => $data['pageData']['page'],
                ]);
            }else {
                $this->error('暂无数据');
            }

        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

}