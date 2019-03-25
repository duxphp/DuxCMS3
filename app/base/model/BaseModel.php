<?php

/**
 * 基础模型封装
 */
namespace app\base\model;

use dux\kernel\Model;

class BaseModel extends Model {

    protected $error = '';
    protected $data = [];
    protected $infoModel = [];
    protected $primary = '';

    protected $validateRule = [];
    protected $formatRule = [];
    protected $intoFiled = [];
    protected $outFiled = [];

    public function __construct() {
        parent::__construct();
        if (empty($this->primary)) {
            $this->primary = $this->infoModel['pri'];
        }
        if (empty($this->formatRule)) {
            $this->formatRule = $this->infoModel['format'];
        }
        if (empty($this->validateRule)) {
            $this->validateRule = $this->infoModel['validate'];
        }
        if (empty($this->intoFiled)) {
            $this->into($this->infoModel['into']);
        }
        if (empty($this->outFiled)) {
            $this->out($this->infoModel['out']);
        }

        //加载基础函数
        require_once(APP_PATH . 'base/util/Function.php');
    }

    /**
     * 创建录入数据
     * @param array $data
     * @param string $time
     * @return array|bool
     */
    public function create($data = [], $time = null) {
        if (empty($data)) {
            $data = request('post');
        }
        if (empty($data)) {
            $this->error = '创建数据不存在!';
            return false;
        }
        //限制入库字段
        if (!empty($this->intoFiled)) {
            $newData = array();
            foreach ($this->intoFiled as $value) {
                if (isset($data[$value])) {
                    $newData[$value] = $data[$value];
                }
            }
            $data = $newData;
        }
        //销毁入库字段
        if (!empty($this->outFiled)) {
            foreach ($this->outFiled as $value) {
                unset($data[$value]);
            }
        }
        //判断时机
        if (empty($time)) {
            if (empty($data[$this->primary])) {
                $time = 'add';
            } else {
                $time = 'edit';
            }
        }
        $this->data = $data;
        if (!$this->formatData($this->formatRule, $time)) {
            return false;
        }
        if (!$this->validateData($this->validateRule, $time)) {
            return false;
        }
        return $this->data;
    }

    /**
     * 规定写入字段
     * @param $field
     * @return $this
     */
    public function into($field) {
        if (empty($field)) {
            $this->intoFiled = array();
            return $this;
        }
        $this->intoFiled = explode(',', $field);
        return $this;
    }

    /**
     * 规定过滤字段
     * @param $field
     * @return $this
     */
    public function out($field) {
        if (empty($field)) {
            $this->outFiled = array();
            return $this;
        }
        $this->outFiled = explode(',', $field);
        return $this;
    }

    /**
     * 设置验证规则
     * @param array $rules
     * @return $this
     */
    public function validate($rules = []) {
        $this->validateRule = $rules;
        return $this;
    }

    /**
     * 设置格式化规则
     * @param array $rules
     * @return $this
     */
    public function format($rules = []) {
        $this->formatRule = $rules;
        return $this;
    }


    /**
     * 格式化录入数据
     * @param array $formatRule
     * @param string $time
     * @return bool
     */
    public function formatData($formatRule = [], $time = 'all') {
        //获取自动处理
        if (empty($formatRule)) {
            return true;
        }
        $data = $this->data;
        $filter = new \dux\lib\Filter();
        foreach ($formatRule as $field => $val) {
            foreach ($val as $method => $v) {
                list($params, $trigger, $type) = $v;
                $type = isset($type) ? $type : 1;
                if (!$type) {
                    if (!isset($data[$field])) {
                        continue;
                    }
                }
                //格式化数据
                if ($trigger == $time || $trigger == 'all') {
                    //格式化规则
                    switch ($method) {
                        case 'callback':
                            $data[$field] = call_user_func_array(array(&$this, $params), [$field, $data[$field]]);
                            break;
                        case 'field':
                            $data[$field] = $data[$params];
                            break;
                        case 'ignore':
                            if (empty($data[$field])) {
                                unset($data[$field]);
                            }
                            break;
                        case 'string':
                            if (empty($data[$field])) {
                                $data[$field] = $params;
                            }
                            break;
                        default:
                            //格式化其他类型
                            $method = 'filter' . ucfirst($method);
                            if (!method_exists($filter, $method)) {
                                $this->error = '过滤方法['.$method.']不存在!';
                                return false;
                            } else {
                                $data[$field] = call_user_func_array([$filter, $method], [$field, $data[$field], $params]);
                            }
                            break;
                    }
                }
            }
        }
        if (empty($data)) {
            $this->error = '格式化后数据不存在!';
            return false;
        }
        $this->data = $data;
        return true;
    }

    /**
     * 验证录入数据
     * @param array $validateRule
     * @param string $time
     * @return bool
     */
    public function validateData($validateRule = [], $time = 'all') {
        if (empty($validateRule)) {
            return true;
        }
        $data = $this->data;
        $filter = new \dux\lib\Filter();
        foreach ($validateRule as $field => $val) {
            foreach ($val as $method => $v) {
                list($params, $msg, $where, $trigger) = $v;
                if (empty($trigger)) {
                    $trigger = 'all';
                }
                $value = $data[$field];
                //字段存在验证
                if ($where == 'exists') {
                    if (!isset($data[$field])) {
                        continue;
                    }
                }
                //不为空验证
                if ($where == 'value') {
                    if (empty($value)) {
                        continue;
                    }
                }
                if ($trigger == $time || $trigger == 'all') {
                    //验证规则
                    switch ($method) {
                        case 'callback':
                            if(!call_user_func_array(array(&$this, $params), [$field, $value])) {
                                $this->error = $msg;
                                return false;
                            }
                            break;
                        case 'confirm':
                            //字段相等
                            if ($value == $data[$params]) {
                                $this->error = $msg;
                                return false;
                            }
                            break;
                        case 'unique':
                            //判断唯一值
                            $where = [];
                            $where[$field] = $value;
                            if ($time == 'edit') {
                                $where['_sql'] = $this->primary . '!=' . $data[$this->primary];
                            }
                            if ($this->where($where)->count()) {
                                $this->error = $msg;
                                return false;
                            }
                            break;
                        default:
                            //验证其他表单
                            $method = 'validate' . ucfirst($method);
                            if (!method_exists($filter, $method)) {
                                $this->error = '验证规则不存在!';
                                return false;
                            }
                            if (!call_user_func_array([$filter, $method], [$field, $value, $params])) {
                                $this->error = $msg;
                                return false;
                            }
                            break;
                    }
                }
            }
        }
        return true;
    }

    /**
     * 获取处理数据
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * 获取主键
     * @return string
     */
    public function getPrimary() {
        return $this->primary;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * 简化添加数据
     * @param array $data
     * @return bool
     */
    public function add($data = []) {
        unset($data[$this->primary]);
        return $this->data($data)->insert();
    }

    /**
     * 简化编辑数据
     * @param array $data
     * @return bool
     */
    public function edit($data = []) {
        $where = [];
        $where[$this->primary] = $data[$this->primary];
        return $this->data($data)->where($where)->update();
    }

    /**
     * 简化数据删除
     * @param $id
     * @return bool
     */
    public function del($id) {
        $where = [];
        $where[$this->primary] = $id;
        return $this->where($where)->delete();
    }


}