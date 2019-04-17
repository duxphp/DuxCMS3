<?php
namespace app\site\service;

use function GuzzleHttp\json_decode;

/**
 * 标签接口
 */
class LabelService {

    /**
     * 搜索列表
     * @param $data
     * @return mixed
     */
    public function search($data) {
        return target('site/Search', 'middle')->setParams([
            'app' => $data['app'],
            'limit' => $data['limit'],
            'order' => $data['order']
        ])->export(function ($data, $msg) {
            return $data;
        }, function ($message, $code) {
            return [];
        });
    }
    
    /**
     * 碎片内容
     * @param $data
     * @return mixed
     */
    public function fragment($data) {
        $where = [];
        $where['fragment_id'] = $data['id'];
        if (!empty($data['where'])) {
            $where['_sql'] = $data['where'];
        }
        $info = target('site/SiteFragment')->getInfo($data['id']);
        return html_out($info['content']);
    }

    /**
     * 自定义列表
     */
    public function diyList($data) {
        $where = [];
        if (isset($data['id'])) {
            $where['diy_id'] = $data['id'];
        }
        //其他条件
        if (!empty($data['where'])) {
            $where['_sql'][] = $data['where'];
        }
        //调用数量
        if (empty($data['limit'])) {
            $data['limit'] = 10;
        }
        //内容排序
        if (empty($data['order'])) {
            $data['order'] = 'sort asc, data_id asc';
        }
        //其他属性
        $list = target('site/SiteDiyData')->loadList($where, $data['limit'], $data['order']);
        foreach ($list as $key => $value) {
            $list[$key] = array_merge($value, $value['expend'] ? json_decode($value['expend']) : []);
            unset($list[$key]['expend']);
        }
        return $list;
    }

}
