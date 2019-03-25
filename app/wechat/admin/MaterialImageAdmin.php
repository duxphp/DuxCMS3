<?php

/**
 * 图片素材
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class MaterialImageAdmin extends \app\wechat\admin\WechatAdmin {


    protected $_model = 'WechatMaterialImage';
    private $material = null;

    public function __construct() {
        parent::__construct();
        $this->material = $this->wechat->material;
    }

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return array(
            'info' => array(
                'name' => '图片素材',
                'description' => '管理微信素材库素材',
            ),
            'fun' => [
                'index' => true
            ]
        );
    }

    public function _indexOrder() {
        return 'time desc, material_id desc';
    }

    /**
     * 获取平台数据
     */
    public function data() {
        $curPage = request('post', 'page', 1);
        $page = 20;
        $stats = $this->material->stats();
        $newsCount = $stats->news_count;
        $pagesObj = new \dux\lib\Pagination($newsCount, $curPage, $page);
        $pageInfo = $pagesObj->build();
        $data = $this->material->list('image', $pageInfo['offset'], $page);
        $items = $data['item'];
        foreach ($items as $key => $vo) {
            $items[$key]['image'] = url('wechat/util/image', ['url' => $vo['url']]);
        }
        $this->success($items);
    }

    /**
     * 同步素材
     */
    public function sync() {
        $stats = $this->material->stats();
        $imageCount = $stats->image_count;
        $curPage = request('post', 'page', 1);
        if ($curPage == 1) {
            $status = target($this->_model)->where([
                '_sql' => 'material_id > 0'
            ])->delete();
            if (!$status) {
                $this->error('清空本地数据失败!');
            }
        }
        $page = 20;
        $pagesObj = new \dux\lib\Pagination($imageCount, $curPage, $page);
        $pageInfo = $pagesObj->build();
        if ($curPage > $pageInfo['page']) {
            $this->error('同步完成!', url('index'));
        }
        $offset = $pageInfo['offset'];
        $data = $this->material->list('image', $offset, $page);
        $list = $data->item;
        foreach ($list as $key => $vo) {
            if (!$this->getImage($vo['url'], $vo['media_id'], $vo['update_time'])) {
                return false;
            }
        }
        $data = [
            'max' => $pageInfo['page'],
            'num' => $curPage
        ];
        $this->success($data);
    }

    private function getImage($url, $mediaId, $time) {
        $urlParams = $this->getUrlParams($url);
        $ext = $urlParams['wx_fmt'] ? $urlParams['wx_fmt'] : 'jpg';
        $ext = ($ext == 'jpeg') ? 'jpg' : $ext;
        $imgData = $this->material->get($mediaId);
        if (empty($imgData)) {
            $this->error('素材采集失败!');
        }
        $dir = ROOT_PATH . '/upload/weichat/image/';
        $image = '/upload/weichat/image/' . $mediaId . '.' . $ext;
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0777, true)) {
                $this->error('目录没有写入权限!');
            }
        }
        if (!file_put_contents(ROOT_PATH . $image, $imgData)) {
            $this->error('本地素材抓取失败,请刷新再试!');
        }
        $data = [
            'media_id' => $mediaId,
            'image' => $image,
            'url' => $url,
            'time' => $time
        ];
        if (!target($this->_model)->add($data)) {
            $this->error('素材保存失败!');
        }
        return true;
    }

    private function getUrlParams($url) {
        $urlInfo = parse_url($url);
        $query = $urlInfo['query'];
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    public function add() {
        if (!isPost()) {
            $this->systemDisplay('info');
        } else {
            $image = request('post', 'image');
            if (empty($image)) {
                $this->error('请先上传图片!');
            }
            $imageFile = ROOT_PATH . $image;
            $result = $this->material->uploadImage($imageFile);
            if (!$result->media_id) {
                $this->error('素材上传失败');
            }
            $data = [
                'media_id' => $result->media_id,
                'image' => $image,
                'url' => $result->url,
                'time' => time()
            ];
            if (!target($this->_model)->add($data)) {
                $this->error('素材保存失败!');
            }
            $this->success('素材上传成功!', url('index'));
        }
    }

    public function del() {
        $id = request('post', 'id');
        if (empty($id)) {
            $this->error('参数不正确!');
        }
        $info = target($this->_model)->getInfo($id);
        if (empty($info)) {
            $this->error('该素材不存在!');
        }
        $this->material->delete($info['media_id']);
        target($this->_model)->del($id);
        $this->success('素材删除成功!');
    }

    public function dialog() {
        $where = [];
        $count = target($this->_model)->countList($where);
        $pageData = $this->pageData($count, 12);
        $list = target($this->_model)->loadList($where, $pageData['limit'], 'material_id desc');

        $this->assign('list', $list);
        $this->assign('page', $pageData['html']);
        $this->dialogDisplay();

    }


}