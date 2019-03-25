<?php

namespace app\tools\api;

/**
 * 队列运行
 */

ignore_user_abort(true);
set_time_limit(0);

class QueueApi {

    private $cache = null;
    private $lockName = 'queue.status';

    public function __construct() {
        $config = \dux\Config::get('dux.use');
        $this->cache = \dux\Dux::cache($config['tpl_cache']);
    }

    /**
     * 执行计划任务
     */
    public function index() {
        if ($this->lockStatus()) {
            exit('队列锁定中');
        }
        $config = target('tools/ToolsQueueConfig')->getConfig();
        if (!$config['status']) {
            $this->lock(false);
            exit('队列未运行');
        }
        $where = [];
        $where['status'] = 1;
        $where['_sql'] = 'start_time <= ' . time() . ' AND run_num < max_num';
        $list = target('tools/ToolsQueue')->where($where)->limit($config['every_num'])->order('queue_id asc')->select();
        if (empty($list)) {
            exit('没有任务');
        }
        $this->lock(true, $config['lock_time']);
        foreach ($list as $vo) {

            if($vo['run_time']) {
                //重试间隔1分钟
                if($vo['run_time'] + 60 > time()) {
                    continue;
                }
            }

            if ($vo['run_num'] >= $vo['max_num']) {
                continue;
            }
            target('tools/ToolsQueue')->where(['queue_id' => $vo['queue_id']])->setInc('run_num', 1);
            $status = false;
            $message = 'ok';
            try {
                $action = $vo['action'];
                if (target($vo['target'], $vo['layer'])->$action(json_decode($vo['params'], true), $vo['has_id'])) {
                    $status = true;
                } else {
                    $message = target($vo['target'], $vo['layer'])->getError();
                    $message = $message ? $message : '未返回失败';
                }
            } catch (\Exception $err) {
                $message = $err->getMessage();
            }
            $statusData = 1;
            if ($status) {
                $statusData = 2;
            } else {
                if (($vo['run_num'] + 1) >= $vo['max_num']) {
                    $statusData = 0;
                }
            }
            target('tools/ToolsQueue')->edit([
                'queue_id' => $vo['queue_id'],
                'status' => $statusData,
                'run_time' => time(),
                'message' => $message
            ]);

            if ($status && $config['del_status']) {
                target('tools/ToolsQueue')->del($vo['queue_id']);
            }
        }
        $this->lock(false);
        exit('执行完成');
    }

    /**
     * 锁定状态
     * @param bool $status
     * @param int $time
     */
    private function lock($status = true, $time = 60) {
        if ($status) {
            $this->cache->set($this->lockName, time(), $time);
        } else {
            $this->cache->del($this->lockName);
        }
    }

    /**
     * 状态查询
     * @return bool
     */
    private function lockStatus() {
        if ($this->cache->get($this->lockName)) {
            return true;
        } else {
            return false;
        }
    }

}