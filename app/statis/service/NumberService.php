<?php

namespace app\statis\service;

/**
 * 数量统计
 */
class NumberService extends \app\base\service\BaseService {

    public function statis($data) {
        $data = [
            'user_id' => intval($data['user_id']),
            'has_id' => intval($data['has_id']),
            'species' => html_clear($data['species']),
            'date' => $data['date'] ? $data['date'] : date('Ymd'),
            'type' => isset($data['type']) ? intval($data['type']) : 1,
            'num' => isset($data['num']) ? intval($data['num']) : 1,
        ];
        $model = target('base/Base');
        $info = $model->table('statis_number')->where(['user_id' => $data['user_id'], 'date' => $data['date'], 'species' => $data['species'], 'has_id' => $data['has_id']])->lock(true)->find();
        if (empty($info)) {
            $key = $data['type'] ? 'inc_num' : 'dec_num';
            $status = $model->table('statis_number')->data([
                'user_id' => $data['user_id'],
                'species' => $data['species'],
                'has_id' => $data['has_id'],
                'date' => $data['date'],
                $key => $data['num'],
            ])->insert();
            if (!$status) {
                return $this->error('服务繁忙，请稍后重试！');
            }
        } else {
            if ($data['type']) {
                $status = $model->table('statis_number')->where(['num_id' => $info['num_id']])->setInc('inc_num', $data['num']);
            } else {
                $status = $model->table('statis_number')->where(['num_id' => $info['num_id']])->setInc('dec_num', $data['num']);
            }
            if (!$status) {
                return $this->error('服务繁忙,请稍候再试!');
            }
        }
        return $this->success();
    }
}
