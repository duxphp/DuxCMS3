<?php
namespace app\page\service;

/**
 * 标签接口
 */
class LabelService {

    /**
     * 内容列表
     */
    public function pageList($data) {
        $where = [];
        //上级栏目
        if (isset($data['parent_id'])) {
            $where['parent_id'] = $data['parent_id'];
        }
        //指定栏目
        if (!empty($data['page_id'])) {
            $where['_sql'][] = 'page_id in (' . $data['page_id'] . ')';
        }
        //是否带形象图
        if (isset($data['image'])) {
            if ($data['image'] == true) {
                $where['_sql'][] = 'image <> ""';
            } else {
                $where['image'] = '';
            }
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
            $data['order'] = 'sort asc, create_time desc, page_id desc';
        }
        //其他属性
        $where['status'] = 1;
        return target('page/Page')->loadList($where, $data['limit'], $data['order']);
    }

}
