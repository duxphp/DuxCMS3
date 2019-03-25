<?php
namespace app\base\service;
/**
 * 基础服务接口
 */
class BaseService {

    protected $error = '服务未处理成功!';

    /**
     * 失败返回
     * @param $msg
     * @return bool
     */
    protected function error($msg = '') {
        if(!empty($msg)){
            $this->error = $msg;
        }
        return false;
    }

    /**
     * 成功返回
     * @param bool $data
     * @return bool
     */
    protected function success($data = true) {
        return $data;
    }

    /**
     * 获取错误消息
     * @return string
     */
    public function getError() {
        return $this->error;
    }

}
