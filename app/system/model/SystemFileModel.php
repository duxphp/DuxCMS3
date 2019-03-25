<?php

namespace app\system\model;

use app\system\model\SystemModel;

/**
 * 文件上传操作
 */
class SystemFileModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'file_id',
        'format' => [
        ],
        'into' => '',
        'out' => '',
    ];

    /**
     * 上传数据
     * @param string $remote
     * @return bool
     */
    public function uploadData($remote = '') {
        $config = [];
        $config['dir_name'] = date('Y-m-d');
        //处理图片大小
        $width = request('post', 'width');
        $height = request('post', 'height');
        if ($width && $height) {
            $config['thumb_width'] = $width;
            $config['thumb_height'] = $height;
            $config['thumb_status'] = true;
            $config['thumb_type'] = 2;
        }
        $data = target('base/Upload')->upload($config, $remote);
        if (!$data) {
            $this->error = target('base/Upload')->getError();
            return false;
        }
        foreach ($data as $vo) {
            $vo['user_id'] = USER_ID;
            $this->addData('add', $vo);
        }
        return $data;
    }

    /**
     * 添加信息
     * @param string $data 增加数据
     * @return bool 更新状态
     */
    public function addData($type, $data) {
        $data = $this->create($data);
        return $this->add($data);
    }

}
