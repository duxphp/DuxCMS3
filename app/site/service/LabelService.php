<?php
namespace app\site\service;

/**
 * 标签接口
 */
class LabelService {

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

}
