<?php

/**
 * 地区库导入
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;

class RegionAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SystemApplication';

    public function index() {
        $init = ROOT_PATH . 'region/0.json';
        $data = file_get_contents($init);
        $data = json_decode($data, true);
        print_r($data);
    }


}