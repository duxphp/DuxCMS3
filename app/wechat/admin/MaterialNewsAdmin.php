<?php

/**
 * 图文素材
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\wechat\admin;


class MaterialNewsAdmin extends \app\wechat\admin\WechatAdmin {


    protected $_model = 'WechatMaterialNews';
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
                'name' => '图文素材',
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
        $data = $this->material->list('news', $pageInfo['offset'], $page);
        $items = $data['item'];
        foreach ($items as $key => $vo) {
            foreach ($vo['content']['news_item'] as $k => $v) {
                $items[$key]['content']['news_item'][$k]['image'] = url('wechat/util/image', ['url' => $v['thumb_url']]);
            }
        }
        $this->success($items);
    }


}