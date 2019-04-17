<?php
namespace app\site\api;

/**
 * 搜索API
 */

class SearchApi extends \app\base\api\BaseApi {

    protected $_middle = 'site/Search';

    /**
     * 搜索记录列表
     * @method GET
     * @param string $app 应用名，可选
     * @param inetger $limit 记录条数，默认10条
     * @param string $order 排序类型，默认搜索次数，create 创建时间、update 更新时间，hot 热门搜索
     * @return inetger $code 200
     * @return string $message ok
     * @return json $result [{"keyword": "关键词", "num": 5, "app": "article", "create_time": 1546272000, "update_time": 1546272000}]
     * @field inetger $search_id 关键词ID
     * @field string $keyword 关键词 
     * @field string $num 搜索次数 
     * @field string $app 应用名
     * @field string $create_time 创建时间 
     * @field string $update_time 更新时间
     */
    public function index() {
        target($this->_middle, 'middle')->setParams([
            'app' => $this->data['app'],
            'limit' => $this->data['limit'],
            'order' => $this->data['order']
        ])->export(function ($data, $msg) {
            $this->success($msg, $data);
        }, function ($message, $code) {
            $this->error($message, $code);
        });
    }

}