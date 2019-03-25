<?php

/**
 * 基础模型封装
 */
namespace app\system\model;

use app\base\model\BaseModel;

class SystemModel extends BaseModel {


    /**
     * 获取列表
     * @return array
     */
    public function loadList() {
        $params = func_get_args();
        $where = !empty($params[0]) ? $params[0] : [];
        $limit = !empty($params[1]) ? $params[1] : 0;
        $order = !empty($params[2]) ? $params[2] : $this->getPrimary() . ' desc';
        return $this->where($where)->limit($limit)->order($order)->select();
    }

    /**
     * 获取数量
     * @param array $where
     * @return mixed
     */
    public function countList($where = []) {
        return $this->where($where)->count();
    }

    /**
     * 获取信息
     * @param $id
     * @return array|bool
     */
    public function getInfo($id) {
        if (empty($this->primary)) {
            return false;
        }
        $where = [];
        $where[$this->primary] = $id;
        return $this->getWhereInfo($where);
    }

    /**
     * 获取信息
     * @param $where
     * @return array
     */
    public function getWhereInfo($where) {
        return $this->where($where)->find();
    }

    /**
     * 自动保存信息
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function saveData($type = 'add', $data = []) {
        $data = $this->create($data);
        if (!$data) {
            return false;
        }
        if (method_exists($this, '_saveBefore')) {
            $data = $this->_saveBefore($data, $type);
        }
        if (!$data) {
            return false;
        }
        $table = $this->_getTable();
        $table = str_replace($this->prefix, '', $table);
        $hookList = run('service', 'Model', 'saveBefore', [$type, $data, $table, $this->primary]);
        if (!empty($hookList)) {
            foreach ($hookList as $a => $v) {
                if (!$v) {
                    $this->error = target($a . '/Order', 'service')->getError();
                    return false;
                }
            }
        }
        if ($type == 'add') {
            if (method_exists($this, '_addBefore')) {
                $data = $this->_addBefore($data);
            }

            $id = $this->add($data);
            $data[$this->primary] = $id;
            if (!$id) {
                return false;
            }
            if (method_exists($this, '_saveAfter')) {
                if (!$this->_saveAfter($type, $data)) {
                    return false;
                }
            }
            $hookList = run('service', 'Model', 'saveAfter', [$type, $data, $table, $this->primary]);
            if (!empty($hookList)) {
                foreach ($hookList as $a => $v) {
                    if (!$v) {
                        $this->error = target($a . '/Order', 'service')->getError();
                        return false;
                    }
                }
            }
            return $id;
        }
        if ($type == 'edit') {
            if (method_exists($this, '_editBefore')) {
                $data = $this->_editBefore($data);
            }
            if (empty($data[$this->primary])) {
                return false;
            }
            if (!$this->edit($data)) {
                return false;
            }
            if (method_exists($this, '_saveAfter')) {
                if (!$this->_saveAfter($type, $data)) {
                    return false;
                }
            }
            $hookList = run('service', 'Model', 'saveAfter', [$type, $data, $table, $this->primary]);
            if (!empty($hookList)) {
                foreach ($hookList as $a => $v) {
                    if (!$v) {
                        $this->error = target($a . '/Order', 'service')->getError();
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 删除信息
     * @param $id
     * @return bool
     */
    public function delData($id) {
        if (method_exists($this, '_delBefore')) {
            if(!$this->_delBefore($id)){
                return false;
            }
        }
        $table = $this->_getTable();
        $table = str_replace($this->prefix, '', $table);
        $hookList = run('service', 'Model', 'delBefore', [$id, $table, $this->primary]);
        if (!empty($hookList)) {
            foreach ($hookList as $a => $v) {
                if (!$v) {
                    $this->error = target($a . '/Order', 'service')->getError();
                    return false;
                }
            }
        }
        $where = array();
        $where[$this->primary] = $id;
        if (!$this->where($where)->delete()) {
            return false;
        }
        if (method_exists($this, '_delAfter')) {
            if(!$this->_delAfter($id)){
                return false;
            }
        }
        $hookList = run('service', 'Model', 'delAfter', [$id, $table, $this->primary]);
        if (!empty($hookList)) {
            foreach ($hookList as $a => $v) {
                if (!$v) {
                    $this->error = target($a . '/Order', 'service')->getError();
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 获取树形结构
     * @param $data
     * @param int $parentId
     * @param array $field
     * @return array
     */
    public function getTree($data, $parentId = 0, $field = ['parent_id', 'class_id']) {
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v[$field[0]] == $parentId) {
                $v['children'] = $this->getTree($data, $v[$field[1]], $field);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * html树形结构
     * @param int $parentId
     * @param int $id
     * @param array $field
     * @return string
     */
    public function getHtmlTree($parentId = 0, $id = 0, $field = ['parent_id', 'class_id', 'name']) {
        $data = $this->loadList();
        $tree = $this->getTree($data, $parentId, $field);
        return $this->_getHtmlTree($tree, $id, $field);
    }

    private function _getHtmlTree($tree, $id = 0, $field) {
        if (empty($tree)) {
            return '';
        }
        $html = '';
        foreach ($tree as $t) {
            if ($id == $t[$field[1]]) {
                $html .= "<li class='active'><h2>";
            } else {
                $html .= "<li><h2>";
            }

            if (empty($t['children'])) {
                $html .= "<a href='" . url('index', array($field[1] => $t[$field[1]])) . "'>{$t[$field[2]]}</a></h2></li>";
            } else {
                $html .= "<a href='" . url('index', array($field[1] => $t[$field[1]])) . "'>" . $t[$field[2]] . "</a></h2>";
                $html .= $this->_getHtmlTree($t['children'], $id, $field);
                $html = $html . "</li>";
            }
        }
        return $html ? '<ul>' . $html . '</ul>' : $html;
    }

}
