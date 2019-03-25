<?php

/**
 * 队列设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;


class QueueConfAdmin extends \app\system\admin\SystemAdmin {

    protected $_model = 'ToolsQueueConfig';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return array(
            'info' => array(
                'name' => '队列设置',
                'description' => '队列功能相关参数设置',
            ),
        );
    }

    public function index() {
        if(!isPost()) {
            $info = target('ToolsQueueConfig')->getConfig();
            $this->assign('info', $info);
            $this->systemDisplay();
        }else{
            if(target('ToolsQueueConfig')->saveInfo()){
                $this->success('功能配置成功！');
            }else{
                $this->error('功能配置失败');
            }
        }
    }

}