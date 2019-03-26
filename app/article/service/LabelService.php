<?php
namespace app\article\service;

/**
 * 标签接口
 */
class LabelService {

    /**
     * 栏目列表
     */
    public function classList($data) {
        $where = array();
        //上级栏目
        if (isset($data['parent_id'])) {
            $where['parent_id'] = $data['parent_id'];
        }
        //指定栏目
        if (!empty($data['class_id'])) {
            $where['_sql'][] = 'class_id in (' . $data['class_id'] . ')';
        }
        //其他条件
        if (!empty($data['where'])) {
            $where['_sql'][] = $data['where'];
        }
        return target('article/ArticleClass')->loadList($where, $data['limit']);
    }

    /**
     * 内容列表
     */
    public function contentList($data) {
        $where = [];
        //指定栏目内容
        if (!empty($data['class_id'])) {
            $classWhere = 'B.class_id in (' . $data['class_id'] . ')';
        }
        //指定栏目下子栏目内容
        if ($data['sub'] && !empty($data['class_id'])) {
            $classIds = target('article/ArticleClass')->getSubClassId($data['class_id']);
            if (!empty($classIds)) {
                $classWhere = "B.class_id in ({$classIds})";
            }
        }
        if (!empty($classWhere)) {
            $where['_sql'][] = $classWhere;
        }
        //是否带形象图
        if (isset($data['image'])) {
            if ($data['image'] == true) {
                $where['_sql'][] = 'A.image <> ""';
            } else {
                $where['A.image'] = '';
            }
        }
        //推荐位
        if (!empty($data['pos_id'])) {
            $where['_sql'][] = 'FIND_IN_SET(' . $data['pos_id'] . ', A.pos_id)';
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
            $data['order'] = 'A.sort asc, A.create_time desc, A.article_id desc';
        }
        //模型调用
        $data['model_id'] = intval($data['model_id']);
        //其他属性
        $where['A.status'] = 1;
        return target('article/Article')->loadList($where, $data['limit'], $data['order'], $data['model_id']);
    }

}
