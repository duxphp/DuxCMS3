<?php

namespace app\statis\service;
/**
 * 财务统计
 */
class FinanceService extends \app\base\service\BaseService {

    /**
     * 操作账户
     * @param $data
     * @return bool
     */
    public function account($data) {
        $data = [
            'user_id' => intval($data['user_id']),
            'has_species' => $data['species'],
            'sub_species' => $data['sub_species'],
            'has_no' => html_clear($data['no']),
            'money' => price_format($data['money']),
            'pay_no' => html_clear($data['pay_no']),
            'pay_name' => html_clear($data['pay_name']),
            'pay_way' => html_clear($data['pay_way']),
            'type' => isset($data['type']) ? intval($data['type']) : 1,
            'title' => html_clear($data['title']),
            'remark' => html_clear($data['remark']),
        ];
        if (empty($data['user_id'])) {
            return $this->error('无法识别用户!');
        }
        if (bccomp($data['money'], 0, 2) === -1) {
            return $this->error('处理金额不正确!');
        }
        $time = time();
        $date = date('Ymd');
        $model = target('base/Base');
        $financialInfo = $model->table('statis_financial')->where(['user_id' => $data['user_id'], 'date' => $date, 'species' => $data['has_species'], 'sub_species' => $data['sub_species']])->lock(true)->find();
        $financialId = $financialInfo['financial_id'];
        if (empty($financialInfo)) {
            $financialId = $model->table('statis_financial')->data([
                'user_id' => $data['user_id'],
                'species' => $data['has_species'],
                'sub_species' => $data['sub_species'],
                'date' => $date,
            ])->insert();
            if (!$financialId) {
                return $this->error('账户繁忙，请稍后重试！');
            }
        }
        if ($data['type']) {
            $status = $model->table('statis_financial')->where(['financial_id' => $financialId])->data(['charge[+]' => $data['money']])->update();
        } else {
            $status = $model->table('statis_financial')->where(['financial_id' => $financialId])->data(['spend[+]' => $data['money']])->update();
        }
        if (!$status) {
            return $this->error('账户操作繁忙,请稍候再试!');
        }
        //写入记录
        $logData = [];
        $logData['user_id'] = $data['user_id'];
        $logData['log_no'] = log_no($data['user_id']);
        $logData['has_no'] = $data['has_no'];
        $logData['has_species'] = $data['has_species'];
        $logData['sub_species'] = $data['sub_species'];
        $logData['time'] = $time;
        $logData['money'] = $data['money'];
        $logData['title'] = $data['title'];
        $logData['remark'] = $data['remark'];
        $logData['pay_no'] = $data['pay_no'];
        $logData['pay_name'] = $data['pay_name'];
        $logData['pay_way'] = $data['pay_way'];
        $logData['type'] = $data['type'];
        $logId = $model->table('statis_financial_log')->data($logData)->insert();
        if (!$logId) {
            return $this->error('资金处理失败,请稍候再试!');
        }
        return $this->success($logId);
    }
}
