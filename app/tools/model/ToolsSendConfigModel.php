<?php

/**
 * 推送设置
 */
namespace app\tools\model;

use app\system\model\SystemModel;

class ToolsSendConfigModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'config_id',
        'validate' => [
            'type' => [
                'required' => ['', '类型参数获取不正确!', 'must', 'all'],
            ],
        ],
    ];

    /**
     * 获取配置
     * @param $type
     * @return mixed
     */
    public function getConfig($type) {
        $where = array();
        $where['type'] = $type;
        $info = $this->getWhereInfo($where);
        return unserialize($info['setting']);
    }


    /**
     * 获取服务接口
     * @return array
     */
    public function typeList() {
        $list = hook('service', 'Send', 'Type');
        $data = array();
        foreach ($list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        foreach ($data as $key => $vo) {
            $data[$key]['type'] = $key;
        }
        return $data;
    }

    /**
     * 推送类别
     * @return array
     */
    public function classList() {
        $list = hook('service', 'Send', 'Class');
        $classDefault = target('tools/ToolsSendDefault')->loadList();
        $classData = [];
        foreach ($classDefault as $vo) {
            $classData[$vo['class']] = $vo;
        }
        $data = array();
        foreach ($list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        $typeList = $this->typeList();
        foreach ($data as $key => $vo) {
            $typeInfo = $typeList[$classData[$key]['type']];
            $data[$key]['var'] = $typeInfo['var'];
            $data[$key]['class'] = $key;
            $data[$key]['default'] = $classData[$key]['type'];
            $data[$key]['tpl'] = $classData[$key]['tpl'];
        }

        return $data;
    }

    /**
     * 默认类型
     * @param $class
     * @return array|mixed
     */
    public function defaultType($class) {
        $where = array();
        $where['class'] = $class;
        $info = target('tools/ToolsSendDefault')->getWhereInfo($where);
        if(empty($info)) {
            return false;
        }
        $typeList = $this->typeList();
        $typeInfo = $typeList[$info['type']];
        if(empty($typeInfo)) {
            return false;
        }
        $typeInfo['tpl'] = $info['tpl'];
        return $typeInfo;
    }


}