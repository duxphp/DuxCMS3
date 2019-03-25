<?php

namespace app\system\admin;

/**
 * 系统上传
 */
class UploadAdmin extends \app\system\admin\SystemAdmin {

    /**
     * AJAX上传文件
     */
    public function index() {
        $return = ['status' => 1, 'info' => '上传成功', 'data' => ''];
        $file = target('system/SystemFile');
        $info = $file->uploadData();
        if ($info) {
            $info = reset($info);
            $return['data'] = $info;
        } else {
            $return['status'] = 0;
            $return['info'] = $file->getError();
        }
        $this->json($return);
    }

    /**
     * 编辑器上传
     */
    public function editor() {
        $file = target('system/SystemFile');
        $info = $file->uploadData();
        if ($info) {
            $info = reset($info);
            $return = $info;
            $return['error'] = 0;
        } else {
            $return = [];
            $return['error'] = 1;
            $return['message'] = $file->getError();
        }
        $this->json($return);
    }

    public function remote() {
        $files = $_POST['files'];
        $data = [];
        $file = target('system/SystemFile');

        $return = [];
        $return['error'] = 0;
        foreach ($files as $key => $vo) {
            $info = $file->uploadData($vo);
            if(!$info) {
                $return = [];
                $return['error'] = 1;
                $return['message'] = $file->getError();
                $this->json($return);
                exit;
            }else {
                $info = $info['file'];
                $data[$key] = $info['url'];
            }
        }

        $return['files'] = $data;
        $this->json($return);


    }

    public function attach() {
        $page = request('', 'page', 0, 'intval');
        $type = request('', 'type', '', 'html_clear');
        $where = [];
        $format = [
            'image' => 'jpg,png,bmp,jpeg,gif',
            'media' => 'wmv,mp4,flv,ogg,avi,mpg,mp3,wav',
            'document' => 'doc,docx,xls,xlsx,pptx,ppt,csv',
        ];
        if ($type == 'image') {
            $ext = $format['image'];
        }
        if ($type == 'media') {
            $ext = $format['media'];
        }
        if ($type == 'document') {
            $ext = $format['document'];
        }
        if ($type == 'other') {
            $noExt = $format['image'] . ',' . $format['media'] . ',' . $format['document'];
        }
        if ($ext) {
            $where['ext'] = explode(',', $ext);
        }
        if ($noExt) {
            $where['ext[!]'] = explode(',', $noExt);
        }
        $listLimit = 15;
        $count = target('system/SystemFile')->countList($where);
        $pageObj = new \dux\lib\Pagination($count, $page, $listLimit);
        $pageData = $pageObj->build();
        $limit = [$pageData['offset'], $listLimit];
        $list = target('system/SystemFile')->loadList($where, $limit, 'time desc');

        foreach ($list as $key => $vo) {
            if (in_array($vo['ext'], explode(',', $format['image']))) {
                $list[$key]['src'] = $vo['url'];
            } else if (in_array($vo['ext'], explode(',', $format['media']))) {
                $list[$key]['src'] = ROOT_URL . '/public/system/images/icon-media.png';
            } else if (in_array($vo['ext'], explode(',', $format['document']))) {
                $list[$key]['src'] = ROOT_URL . '/public/system/images/icon-document.png';
            } else {
                $list[$key]['src'] = ROOT_URL . '/public/system/images/icon-file.png';
            }
        }
        $this->success([
            'list' => $list,
            'pageData' => $pageData,
        ]);
    }

}

