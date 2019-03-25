<?php

namespace app\statis\service;

/**
 * 访问统计
 */
class ViewsService extends \app\base\service\BaseService {

    public function statis($data) {
        $data = [
            'user_id' => intval($data['user_id']),
            'has_id' => intval($data['has_id']),
            'species' => html_clear($data['species']),
            'date' => $data['date'] ? $data['date'] : date('Ymd'),
            'num' => isset($data['num']) ? intval($data['num']) : 1,
        ];
        $model = target('base/Base');
        $info = $model->table('statis_views')->where(['user_id' => $data['user_id'], 'date' => $data['date'], 'species' => $data['species'], 'has_id' => $data['has_id']])->find();
        if (empty($info)) {
            $status = $model->table('statis_views')->data([
                'user_id' => $data['user_id'],
                'species' => $data['species'],
                'has_id' => $data['has_id'],
                'date' => $data['date'],
                'num' => $data['num']
            ])->insert();
            if (!$status) {
                return $this->error('服务繁忙，请稍后重试！');
            }
        }else {
            $status = $model->table('statis_views')->where(['view_id' => $info['view_id']])->setInc('num', $data['num']);
            if (!$status) {
                return $this->error('服务繁忙,请稍候再试!');
            }
        }

        return $this->success();
    }
}
