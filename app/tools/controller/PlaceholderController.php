<?php

/**
 * 系统首页
 */

namespace app\tools\controller;

class PlaceholderController extends \app\base\controller\BaseController {

    /**
     * 首页
     */
    public function index() {
        target('tools/Placeholder', 'middle')->setParams([
            'width' => request('get', 'width'),
            'height' => request('get', 'height'),
            'text' => request('get', 'text', '', 'urldecode')
        ])->index()->export(function ($data) {
            header('Content-Type:image/svg+xml');
            echo $data['html'];
        });
    }

}