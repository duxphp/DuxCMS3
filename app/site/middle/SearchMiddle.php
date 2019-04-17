<?php

/**
 * 搜索页面
 */

namespace app\site\middle;

class SearchMiddle extends \app\base\middle\BaseMiddle {

    public function __construct() {
        parent::__construct();
    }

    protected function data() {
        $type = $this->params['app'];
        $limit = $this->params['limit'] ? $this->params['limit'] : 10;
        $orderType = $this->params['order'];
        $where = [];
        if($type) {
            $where['app'] = $type;
        }
        $where['num[>]'] = 2;
        switch($orderType) {
            case 'create':
            $order = 'create_time desc';
            break;
            case 'uupdate':
            $order = 'update_time desc';
            break;
            case 'hot':
            $where['num[>]'] = 5;
            $order = 'update_time desc';
            break;
            default:
            $order = 'num desc';
        }
        $searchList = target('site/SiteSearch')->loadList($where, $limit, $order);
        return $this->run($searchList);
    }

}