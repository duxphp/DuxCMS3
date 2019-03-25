<?php

/**
 * 前端日志
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\system\admin;

class SystemLogAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'SystemDebug';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '前端日志',
                'description' => '管理前端日志信息',
            ],
            'fun' => [
                'index' => true,
                'del' => true,
            ],
        ];
    }

    public function _indexCount($where) {
        $files = glob(ROOT_PATH . 'data/log/*.log');
        return count($files);
    }

    public function _indexData($where, $limit, $order) {
        $files = glob(ROOT_PATH . 'data/log/*.log');
        $data = [];
        foreach ($files as $key => $vo) {
            $fileInfo = pathinfo($vo);
            $data[] = [
                'name' => $fileInfo['basename'],
            ];
        }
        $data = array_reverse($data);
        $data = array_slice($data, $limit[0], $limit[1]);
        return $data;
    }

    public function info() {
        $name = request('', 'name');
        $file = file_get_contents(ROOT_PATH . 'data/log/' . $name);

        $data = explode("\n", $file);
        $data = array_reverse($data);

        $newData = [];
        foreach ($data as $key => $vo) {
            if(empty($vo)) {
                continue;
            }
            $info = explode(' ', $vo, 4);
            $newData[] = [
                'time' => $info[1] . ' ' . $info[2],
                'level' => $info[0],
                'info' => $info[3]
            ];
        }
        $this->success(\dux\Dux::view()->fetch('app/system/view/admin/systemlog/info', [
            'list' => $newData
        ]));
    }

}