<?php

/**
 * 分类管理
 */
namespace app\article\model;

use app\system\model\SystemModel;

class ArticleClassModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'class_id',
    ];

    /**
     * 获取分类树
     * @param array $where
     * @param int $limit
     * @param string $order
     * @param int $patrntId
     * @return array
     */
    public function loadTreeList(array $where = [], $limit = 0, $order = '', $patrntId = 0) {
        $class = new \dux\lib\Category(['class_id', 'parent_id', 'name', 'cname']);
        $list = $this->loadList($where, $limit, $order);
        if(empty($list)){
            return [];
        }
        $list = $class->getTree($list, $patrntId);
        return $list;
    }

    /**
     * 获取菜单面包屑
     * @param int $classId 菜单ID
     * @return array 菜单表列表
     */
    public function loadCrumbList($classId)
    {
        $data = $this->loadList();
        $cat = new \dux\lib\Category(['class_id', 'parent_id', 'name', 'cname']);
        $data = $cat->getPath($data, $classId);
        return $data;
    }

    /**
     * 获取子栏目ID
     * @param array $classId 当前栏目ID
     * @return string 子栏目ID
     */
    public function getSubClassId($classId)
    {
        $data = $this->loadTreeList([], 0, '', $classId);
        $list = array();
        $list[] = $classId;
        foreach ($data as $value) {
            $list[]=$value['class_id'];
        }
        return implode(',', $list);
    }

    public function _editBefore($data) {
        if ($data['parent_id'] == $data['class_id']) {
            $this->rollBack();
            $this->error = '您不能将当前分类设置为上级分类!';
            return false;
        }
        $cat = $this->loadTreeList([], 0, '', $data['class_id']);
        if ($cat) {
            foreach ($cat as $vo) {
                if ($data['parent_id'] == $vo['class_id']) {
                    $this->rollBack();
                    $this->error = '不可以将上一级分类移动到子分类';
                    return false;
                }
            }
        }
        return $data;
    }

}