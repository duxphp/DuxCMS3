<?php

/**
 * 财务流水记录
 */

namespace app\statis\model;

use app\system\model\SystemModel;

class StatisFinancialLogModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'log_id',
    ];

    protected function base($where) {
        return $this->table('statis_financial_log(A)')
            ->join('member_user(B)', ['B.user_id', 'A.user_id'])
            ->field(['A.*', 'B.email(user_email)', 'B.tel(user_tel)', 'B.nickname(user_nickname)', 'B.user_id'])
            ->where((array)$where);
    }

    public function loadList($where = [], $limit = 0, $order = 'log_id desc') {
        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        foreach ($list as $key => $vo) {
            $list[$key]['show_name'] = target('member/MemberUser')->getNickname($vo['user_nickname'], $vo['user_tel'], $vo['user_email']);
            $list[$key]['show_time'] = date('Y-m-d H:i', $vo['time']);
        }
        return $list;
    }

    public function countList($where = []) {
        return $this->base($where)->count();
    }

    public function getWhereInfo($where) {
        $info = $this->base($where)->find();
        if ($info) {
            $info['show_name'] = target('member/MemberUser')->getNickname($info['user_nickname'], $info['user_tel'], $info['user_email']);
            $info['show_time'] = date('Y-m-d H:i', $info['time']);
        }
        return $info;
    }

}
