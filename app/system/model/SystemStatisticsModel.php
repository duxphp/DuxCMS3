<?php

/**
 * 访问统计
 */

namespace app\system\model;

use app\system\model\SystemModel;

class SystemStatisticsModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'stat_id'
    ];

    public function countStats($day = 0) {
        $where = '';
        if ($day) {
            $where = 'WHERE date >= ' . date("Ymd", strtotime("-{$day} day"));
        }
        $info = $this->query("SELECT SUM(web) as web, SUM(api) as api, SUM(mobile) as mobile FROM {pre}system_statistics {$where}");
        return $info[0];
    }

    /**
     * 保存统计数据
     * @param $type
     * @param $date
     * @param int $num
     * @return bool
     */
    public function saveStats($type, $date, $num = 1) {
        $where = array();
        $where['date'] = $date;
        $info = target('system/SystemStatistics')->getwhereInfo($where);
        if (empty($info)) {
            target('system/SystemStatistics')->add([
                'date' => $date,
                $type => $num,
            ]);
        } else {
            target('system/SystemStatistics')->where([
                'stat_id' => $info['stat_id']
            ])->setInc($type, $num);
        }
        return true;
    }

}
