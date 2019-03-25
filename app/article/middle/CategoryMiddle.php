<?php

/**
 * 商城分类
 */

namespace app\article\middle;

class CategoryMiddle extends \app\base\middle\BaseMiddle {

    /**
     * 树形分类
     */
    protected function treeList() {
        $list = target('article/ArticleClass')->loadList();
        $treeList = target('article/ArticleClass')->getTree($list);
        return $this->run([
            'treeList' => $treeList
        ]);
    }


}