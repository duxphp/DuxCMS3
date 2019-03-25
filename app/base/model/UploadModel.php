<?php
namespace app\base\model;

/**
 * 上传模块
 */
class UploadModel {

    private $error = '';


    /**
     * 上传数据
     * @param array $config
     * @param string $remote
     * @return array
     */
    public function upload($config = array(), $remote = '') {
        $baseConfig = \dux\Config::get('dux.use_upload');
        $config = array_merge((array)$baseConfig, (array)$config);
        if (empty($config['dir_name'])) {
            $config['dir_name'] = date('Y-m-d');
        }
        $uploadDriver = \dux\Config::get('dux.upload_driver');
        $upConfig = array(
            'maxSize' => intval($config['upload_size']) * 1024 * 1024,
            'allowExts' => explode(',', $config['upload_exts']),
            'rootPath' => ROOT_PATH . 'upload/',
            'savePath' => $config['dir_name'] . '/',
            'saveRule' => 'md5_file',
            'driver' => $config['upload_driver'],
            'driverConfig' => $uploadDriver[$config['upload_driver']],
            'relative' => $config['relative']
        );
        $path = 'upload/' . $config['dir_name'] . '/';



        $upload = new \dux\lib\Upload($upConfig);
        //上传
        if(empty($remote)) {
            //处理图片
            foreach ($_FILES as $key => $info) {
                $file = $info['tmp_name'];
                $fileInfo = pathinfo($info['name']);
                $ext = $fileInfo['extension'];
                $imgType = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
                if (in_array(strtolower($ext), $imgType)) {
                    //设置缩图
                    if ($config['thumb_status']) {
                        $image = new \dux\lib\Image($file, $config['image_driver']);
                        try{
                            $image->thumb($config['thumb_width'], $config['thumb_height'], $config['thumb_type'])->output($file);
                        }catch (\Exception $e) {
                            $this->error = $e->getMessage();
                            return false;
                        }
                    }
                    //设置水印
                    if ($config['water_status']) {
                        $image = new \dux\lib\Image($file, $config['image_driver']);
                        try{
                            $image->water(ROOT_PATH . 'public/watermark/' . $config['water_image'], $config['water_position'])->output($file);
                        }catch (\Exception $e) {
                            $this->error = $e->getMessage();
                            return false;
                        }
                    }
                }
            }
            if (!$upload->upload()) {
                $this->error = $upload->getError();
                return false;
            }
        }else {
            $_FILES = [];
            if (!$upload->uploadRemote($remote)) {
                $this->error = $upload->getError();
                return false;
            }
        }

        //上传信息
        $list = $upload->getUploadFileInfo();

        if (empty($list)) {
            $this->error = '上传文件不存在!';
            return false;
        }

        $returnData = [];
        foreach ($list as $key => $info) {
            $file = $path . $info['savename'];
            if($upConfig['relative']) {
                $pre = '';
            }else {
                $pre = DOMAIN_HTTP;
            }
            $fileUrl = $pre . ROOT_URL . '/' . $file;
            $fileInfo = pathinfo($info['name']);
            $data = array();
            $data['url'] = $info['url'] ? $info['url'] : $fileUrl;
            $data['file'] = $info['url'] ? '' : $file;
            $data['original'] = $info['url'] ? $info['url'] : $fileUrl;
            $data['title'] = $fileInfo['filename'];
            $data['ext'] = $fileInfo['extension'];
            $data['size'] = $info['size'];
            $data['time'] = time();

            $returnData[$key] = $data;
        }
        return $returnData;
    }

    /**
     * 获取错误信息
     */
    public function getError() {
        return $this->error;
    }

}
