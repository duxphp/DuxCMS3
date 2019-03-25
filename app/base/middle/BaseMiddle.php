<?php

/**
 * 基础中间层
 */
namespace app\base\middle;

class BaseMiddle {

    protected $siteConfig = [];
    protected $field = [];
    protected $message = '';
    protected $data = [];
    protected $pageInfo = [
        'title' => '',
        'keyword' => '',
        'description' => '',
        'crumb' => [],
        'name' => ''
    ];
    protected $params = [];
    protected $status = true;
    protected $stop = [
        'message' => '',
        'code' => 500,
        'url' => ''
    ];

    public function __construct() {
        $this->siteConfig = target('site/SiteConfig')->getConfig();
        //加载基础函数
        require_once(APP_PATH . 'base/util/Function.php');
    }

    protected function setCrumb($data) {
        $this->pageInfo['crumb'] = $data;
    }

    protected function setMeta($title = '', $keyword = '', $description = '') {
        $this->pageInfo['title'] = ($title ? $title . ' - ' : '') . $this->siteConfig['info_title'];
        $this->pageInfo['name'] = $title;
        $this->pageInfo['keyword'] = $keyword ? $keyword : $this->siteConfig['info_keyword'];
        $this->pageInfo['description'] = $description ? $description : $this->siteConfig['info_desc'];
    }

    protected function setName($name = '') {
        $this->pageInfo['name'] = $name ? $name : $this->pageInfo['title'];
    }

    public function setParams($params = []) {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function field($keys = []) {
        $newData = [];
        foreach ($this->data as $key => $vo) {
            if (array_key_exists($key, $keys)) {
                $newData[$key] = $vo;
            }
        }
        $this->data = $newData;

        return $this;
    }

    public function export(callable $success = null, callable $stop = null) {
        if (!$this->status) {
            if($stop) {
                return call_user_func_array($stop, $this->stop);
            }
        } else {
            if($success) {
                return $success($this->data, $this->message);
            }
        }
    }

    public function run($data = [], $msg = '') {
        $this->message = $msg;
        $this->data = array_merge((array)$this->data, (array)$data);
        return $this;
    }

    public function stop($message = '', $code = 500, $url = '') {
        $this->status = false;
        $this->stop = [
            'message' => $message ? $message : '内部错误',
            'code' => $code,
            'url' => $url
        ];
        return $this;
    }

    protected function pageData($sumLimit, $pageLimit) {
        $pageObj = new \dux\lib\Pagination($sumLimit, request('', 'page', 1, 'intval'), $pageLimit);
        $pageData = $pageObj->build();

        $limit = [$pageData['offset'], $pageLimit];

        return [
            'limit' => $limit,
            'page' => $pageData['current'],
            'totalPage' => $pageData['page'],
            'raw' => $pageData
        ];
    }

    protected function meta() {
        $data = func_get_args();
        $this->setMeta($data[0]);
        $this->setName($data[1]);
        $this->setCrumb([
            [
                'name' => $data[1],
                'url' => $data[2]
            ]
        ]);

        return $this->run([
            'pageInfo' => $this->pageInfo
        ]);
    }

    public function __call($name, $arguments) {
        if(!$this->status) {
            return $this;
        }
        if(!method_exists($this, $name)) {
            throw new \Exception("Method '{$name}' not found", 500);
        }
        return call_user_func_array([$this, $name], $arguments);
    }

}
