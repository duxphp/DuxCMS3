<?php

/**
 * 字段处理
 */
namespace app\system\model;

class FieldModel {

    private $fieldTpl = '';

    private $fieldClass = 'form-control';

    private $fieldCon = array(
        'name' => '',
        'value' => ''
    );

    private $fieldAttr = '';

    /**
     * 控件类型
     */
    public function typeList() {
        $list = array();
        $list['base'] = [
            'text' => '文本框',
            'textarea' => '多行文本',
            'number' => '数字',
            'email' => '邮箱',
            'date' => '日期(年月日)',
            'month' => '日期(年月)',
            'week' => '日期(年周)',
            'time' => '时间',
            'datetime' => '日期时间',
            'list' => '下拉输入',
            'color' => '颜色选择',
            'radio' => '单选',
            'checkbox' => '多选',
            'select' => '下拉'
        ];
        $list['advanced'] = [
            'upload' => '上传',
            'uploadFile' => '对文件上传',
            'uploadImages' => '多图上传',
            'editor' => '编辑器',
        ];
        return $list;
    }

    /**
     * 文本框类型
     * @return $this
     */
    public function fieldText() {
        $this->fieldTpl = '<input type="text" class="{class}" name="{name}" {attr} value="{value}">';
        return $this;
    }

    /**
     * 设置属性
     * @param $config
     * @return string
     */
    public function attr($config) {
        ksort($config);
        $attr = array();
        foreach($config as $key => $vo) {
            $attr[] = $key . '="'.$vo.'"';
        }
        $this->fieldAttr = implode(' ', $attr);
        return $this;
    }

    /**
     * 设置基本参数
     * @param $config
     * @return $this
     */
    public function config($config) {
        $this->fieldCon = $config;
        return $this;
    }

    /**
     * 编译模板
     */
    public function fetch() {
        $params = [
            'class' => $this->fieldClass,
            'attr' => $this->fieldAttr
        ];
        $params = array_merge($params, $this->fieldCon);
        $tpl = $this->fieldTpl;
        foreach($params as $k => $v) {
            $tpl = str_replace('{'.$k.'}', $v, $tpl);
        }
        return $tpl;
    }



}