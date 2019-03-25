<?php
namespace app\system\service;
/**
 * 统计接口
 */
class StatisticsService {

    private $storage = null;
    private $date = 0;

    public function __construct() {
        $config = \dux\Config::get('dux.use');
        $this->storage = \dux\Dux::storage($config['data_cache']);
        $this->date = date('Ymd');
    }

    /**
     *  新增统计
     * @param $type
     * @return bool
     */
    public function incStats($type) {
        $type = strtolower($type);
        if($type <> 'web' && $type <> 'mobile' && $type <> 'api') {
            return false;
        }
        //$this->refreshStats();
        //保存当天统计
        $dateInfo = json_decode($this->storage->get('stats.' . $this->date), true);
        $dateInfo[$type] = intval($dateInfo[$type]) + 1;
        $this->storage->set('stats.' . $this->date, json_encode($dateInfo));
        return true;
    }

    /**
     * 刷新统计
     * @return bool
     */
    public function refreshStats() {
        $dateList = json_decode($this->storage->get('stats.date'), true);
        if(!empty($dateList)) {
            foreach($dateList as $vo) {
                $dateInfo = json_decode($this->storage->get('stats.' . $vo), true);
                if($dateInfo['web']) {
                    target('system/SystemStatistics')->saveStats('web', $vo, $dateInfo['web']);
                }
                if($dateInfo['api']) {
                    target('system/SystemStatistics')->saveStats('api', $vo, $dateInfo['api']);
                }
                if($dateInfo['mobile']) {
                    target('system/SystemStatistics')->saveStats('mobile', $vo, $dateInfo['mobile']);
                }
                unset($dateList[$vo]);
                $this->storage->del('stats.' . $vo);
            }
        }
        if(empty($dateList[$this->date])) {
            $dateList[$this->date] = $this->date;
        }
        $this->storage->set('stats.date', json_encode($dateList));
        return true;
    }



}
