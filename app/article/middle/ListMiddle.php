<?php

/**
 * 文章列表
 */

namespace app\article\middle;

class ListMiddle extends \app\base\middle\BaseMiddle {

    protected $crumb = [];
    protected $classInfo = [];
    protected $listWhere = [];
    protected $listOrder = [];
    protected $listLimit = 20;
    protected $listModel = 0;

    public function __construct() {
        parent::__construct();
    }

    protected function meta($title = '', $name = '', $url = '') {
        $classId = $this->params['classId'];
        if ($classId) {
            $this->crumb = $this->getCrumb();
            $this->classInfo = $this->getClass();
            $this->setMeta($this->classInfo['name'], $this->classInfo['keyword'], $this->classInfo['description']);
            $this->setCrumb($this->crumb);
        } else {
            $this->setName($name ? $name : '新闻资讯');
            $this->setMeta($title ? $title : '新闻资讯');
            $this->setCrumb([
                [
                    'name' => $title,
                    'url' => $url ? $url : url()
                ]
            ]);
        }
        return $this->run([
            'pageInfo' => $this->pageInfo
        ]);
    }

    private function getClass() {
        if ($this->classInfo) {
            return $this->classInfo;
        }
        $classId = $this->params['classId'];
        if (empty($classId)) {
            return [];
        }
        $this->classInfo = target('article/ArticleClass')->getInfo($classId);

        return $this->classInfo;
    }

    private function getCrumb() {
        if ($this->crumb) {
            return $this->crumb;
        }
        $classId = $this->params['classId'];
        if (empty($classId)) {
            return [];
        }
        $this->crumb = target('article/ArticleClass')->loadCrumbList($classId);

        return $this->crumb;
    }

    protected function classInfo() {
        $this->classInfo = $this->getClass();
        if (empty($this->classInfo)) {
            return $this->run([
                'classInfo' => $this->classInfo,
                'parentClassInfo' => [],
                'topClassInfo' => [],
            ]);
        }
        $this->crumb = $this->getCrumb();
        $parentClassInfo = array_slice($this->crumb, -2, 1);
        if (empty($parentClassInfo)) {
            $parentClassInfo = $this->crumb[0];
        } else {
            $parentClassInfo = $parentClassInfo[0];
        }
        $topClassInfo = $this->crumb[0];

        if ($this->classInfo['tpl_class']) {
            $this->tpl = $this->classInfo['tpl_class'];
        }

        return $this->run([
            'classInfo' => $this->classInfo,
            'parentClassInfo' => $parentClassInfo,
            'topClassInfo' => $topClassInfo,
            'tpl' => $this->tpl
        ]);
    }

    protected function data() {
        $classId = $this->params['classId'];
        $keyword = str_len(html_clear(urldecode($this->params['keyword'])), 10, false);
        $this->params['limit'] = intval($this->params['limit']);
        $listLimit = $this->params['limit'] ? $this->params['limit'] : 20;
        $tag = str_len(html_clear(urldecode($this->params['tag'])), 10, false);
        $classIds = 0;
        if ($classId) {
            $this->classInfo = $this->getClass();
            $classIds = target('article/ArticleClass')->getSubClassId($classId);
            $modelId = $this->classInfo['model_id'];
        }

        $tagInfo = [];
        if ($tag) {
            $tagInfo = target('site/SiteTags')->getWhereInfo([
                'name' => $tag
            ]);
            if (empty($tagInfo)) {
                return $this->stop('标签不存在', 404);
            }
        }
        
        $where = [];

        if ($classIds) {
            $where['_sql'] = 'A.class_id in(' . $classIds . ')';
        }
        if ($keyword) {
            $where['_sql'][] = 'A.title like "%' . $keyword . '%"';
        }
        if ($tagInfo) {
            $where['_sql'][] = 'FIND_IN_SET("' . $tagInfo['tag_id'] . '", A.tags_id)';
        }

        $where['A.status'] = 1;
        $model = target('article/Article');
        $count = $model->countList($where);
        $pageData = $this->pageData($count, $listLimit);
        $list = $model->loadList($where, $pageData['limit'], '', $modelId);

        if ($keyword && $list) {
            target('site/siteSearch')->stats($keyword, 'article');
        }



        return $this->run([
            'tagInfo' => $tagInfo,
            'pageData' => $pageData,
            'countList' => $count,
            'pageList' => $list
        ]);
    }


}