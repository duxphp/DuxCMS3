<?php

/**
 * 管理页面
 */

namespace app\index\admin;

class IndexAdmin extends \app\base\controller\BaseController {

    /**
     * 管理跳转
     */
    public function index() {
        $this->redirect(url('system/Index/index'));
    }

}