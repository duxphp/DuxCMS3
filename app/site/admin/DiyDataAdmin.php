<?php

/**
 * 自定义列表
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\site\admin;


class DiyDataAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SiteDiyData';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '自定义列表',
                'description' => '管理站点自定义列表内容',
            ],
            'fun' => [
                'index' => true,
                'add' => true,
                'edit' => true,
                'del' => true,
            ],
        ];
    }

    public function _indexParam() {
        return [
            'keyword' => 'title',
            'diy_id' => 'diy_id'
        ];
    }

    public function _indexOrder() {
        return 'sort asc, data_id asc';
    }

    public function _indexAssign() {
        return array(
            'diyList' => target('site/SiteDiy')->loadList(),
        );
    }

    private function fields($fields) {
        $fields = array_filter(explode("\n", $fields));
        $fieldsData = [];
        foreach ($fields as $key => $value) {
            $params = explode('|', $value);
            $value = explode(':', $params[1]);
            $fieldsData[] = [
                'title' => trim($params[0]),
                'key' => trim($value[0]),
                'type' => trim($value[1]),
            ];
        }
        return $fieldsData;
    }

    public function _addAssign() {
        $diyId = request('get', 'diy_id', 0, 'intval');
        if (empty($diyId)) {
            $this->error('请选择列表信息！');
        }
        $diyInfo = target('site/SiteDiy')->getInfo($diyId);
        if (empty($diyInfo)) {
            $this->error('列表不存在！');
        }
        return [
            'fieldsData' => $this->fields($diyInfo['fields']),
            'diyId' => $diyId
        ];
    }

    public function _editAssign($info) {
        $diyInfo = target('site/SiteDiy')->getInfo($info['diy_id']);
        if (empty($diyInfo)) {
            $this->error('列表不存在！');
        }
        $fieldsData = $this->fields($diyInfo['fields']);
        $fields = $info['expend'] ? json_decode($info['expend'], true) : '';
        foreach($fieldsData as $key => $vo) {
            $fieldsData[$key]['value'] = $fields[$vo['key']];
        }
        return [
            'fieldsData' => $fieldsData,
            'diyId' => $diyInfo['diy_id']
        ];
    }

    public function _addBefore()
    {
        $diyId = request('post', 'diy_id', 0, 'intval');
        if(empty($diyId)) {
            $this->error('自定义列表不存在！');
        }
    }

}