<?php
namespace app\tools\service;
/**
 * 队列接口
 */
class QueueService extends \app\base\service\BaseService {

    /**
     * 添加队列
     * @param $label 标识
     * @param $hasId 关联id
     * @param $remark 任务说明
     * @param $target 执行模块
     * @param $action 方法名
     * @param $layer 执行层
     * @param array $params 执行参数
     * @param int $startTime 执行时间
     * @return bool
     */
    public function add($label, $hasId, $remark, $target, $action, $layer = '', $params = [], $startTime = 0) {
        $config = target('tools/ToolsQueueConfig')->getConfig();
        $data = [
            'label' => $label,
            'has_id' => $hasId,
            'remark' => $remark,
            'target' => $target,
            'action' => $action,
            'layer' => $layer,
            'params' => json_encode($params),
            'start_time' => $startTime ? $startTime : 0,
            'create_time' => time(),
            'max_num' => $config['retry_num'],
            'status' => 1
        ];
        if(!target('tools/ToolsQueue')->add($data)) {
            return $this->error(target('tools/ToolsQueue')->getError());
        }
        return $this->success('添加队列成功');
    }

    /**
     * 删除队列
     * @param $label
     * @param $hasId
     * @return bool
     */
    public function del($label, $hasId) {
        if(!target('tools/ToolsQueue')->delete([
            'label'=>$label,
            'has_id' => $hasId
        ])) {
            return $this->error(target('tools/ToolsQueue')->getError());
        }
        return $this->success('删除队列成功');

    }

}

