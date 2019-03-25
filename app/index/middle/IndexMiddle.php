<?php

/**
 * 首页信息
 */

namespace app\index\middle;


class IndexMiddle extends \app\base\middle\BaseMiddle {

    public function meta() {
        $this->setMeta('');
        $this->setName('');
        $this->setCrumb([
            [
                'name' => '首页',
                'url' => ROOT_URL . '/'
            ]
        ]);

        return $this->run([
            'pageInfo' => $this->pageInfo
        ]);
    }
}
