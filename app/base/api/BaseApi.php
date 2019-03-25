<?php

/**
 * 基础API
 */

namespace app\base\api;

class BaseApi extends \dux\kernel\Api {

    protected $sysInfo;
    protected $sysConfig;
    protected $apiConfig;
    protected $apiApp = 'main';

    public function __construct() {
        parent::__construct();
        $this->apiApp = $_SERVER['HTTP_APP'];
        $this->sysInfo = \dux\Config::get('dux.info');
        $this->sysConfig = \dux\Config::get('dux.use');
        $this->apiConfig = \dux\Config::get('dux.api');
        $this->checkLink();
        target('system/Statistics', 'service')->incStats('api');
        target('statis/Views', 'service')->statis([
            'species' => 'site',
            'user_id' => $_SERVER['HTTP_AUTHUID'],
            'type' => $_SERVER['HTTP_PLATFORM']
        ]);
        define('PLATFORM', $_SERVER['HTTP_PLATFORM']);
    }

    /**
     * 检查链接
     */
    private function checkLink() {
        $token = $_SERVER['HTTP_TOKEN'];
        $label = $_SERVER['HTTP_LABEL'];
        $key = $this->sysConfig['com_key'];
        if(!empty($label) && $this->apiConfig[$label]) {
            $key = $this->apiConfig[$label]['key'];
        }
        if ($key <> $token) {
            $this->error('接口鉴权失败！', 403);
        }
        //权限检测
        $rule = json_decode($this->apiConfig[$label]['rule']);
        if($rule) {
            if (!in_array(APP_NAME . '.' . MODULE_NAME . '.' . ACTION_NAME, $rule)) {
                $this->error('您没有权限使用该接口！', 403);
            }
        }
    }

    /**
     * 分页数据
     * @param $pageLimit
     * @param $list
     * @param $pageData
     * @return array
     */
    protected function pageData($pageLimit, $list, $pageData) {
        return [
            'limit' => $pageLimit,
            'page' => $pageData['page'],
            'totalPage' => $pageData['totalPage']
        ];
    }

}
