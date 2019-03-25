<?php

/**
 * 搜索API
 */

namespace app\site\api;

class SearchApi extends \dux\kernel\Api {

    public function hot() {
        $this->success('ok', [
            [
                'name' => '泸州老窖',
                'type' => '商品',
                'label' => 'mall'
            ],
            [
                'name' => '茅台',
                'type' => '商品',
                'label' => 'mall'
            ],
            [
                'name' => '五粮液',
                'type' => '商品',
                'label' => 'mall'
            ],
        ]);
    }

}