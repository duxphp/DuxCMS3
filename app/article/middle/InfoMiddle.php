<?php

/**
 * 文章详情
 */

namespace app\article\middle;

class InfoMiddle extends \app\base\middle\BaseMiddle {

    private $crumb = [];
    private $info = [];
    private $classInfo = [];
    private $tpl = '';

    public function __construct() {
        parent::__construct();
        $this->tpl = $this->siteConfig['tpl_content'];
    }

    protected function meta() {
        $this->info = $this->getInfo();
        $this->classInfo = $this->getClass();
        $this->crumb = $this->getCrumb();
        $this->setMeta($this->info['title'] . ' - ' . $this->classInfo['name']);
        $this->setName($this->classInfo['name']);
        $this->setCrumb($this->crumb);
        return $this->run([
            'pageInfo' => $this->pageInfo
        ]);
    }

    private function getInfo() {
        if($this->info) {
            return $this->info;
        }
        $id = $this->params['article_id'];
        if (empty($id)) {
            return [];
        }
        $this->info = target('article/Article')->getInfo($id);
        return $this->info;
    }

    private function getCrumb() {
        if($this->crumb) {
            return $this->crumb;
        }
        $classId = $this->info['class_id'];
        if (empty($classId)) {
            return [];
        }
        $this->crumb = target('article/ArticleClass')->loadCrumbList($classId);
        return $this->crumb;
    }

    private function getClass() {
        if($this->classInfo) {
            return $this->classInfo;
        }
        $this->info = $this->getInfo();
        $classId = $this->info['class_id'];
        if (empty($classId)) {
            return [];
        }
        $this->classInfo = target('article/ArticleClass')->getInfo($classId);
        return $this->classInfo;
    }

    protected function classInfo() {
        $this->classInfo = $this->getClass();
        if (empty($this->classInfo)) {
            return $this->stop('分类不存在！', 404);
        }
        $this->crumb = $this->getCrumb();
        $parentClassInfo = array_slice($this->crumb, -2, 1);
        if (empty($parentClassInfo)) {
            $parentClassInfo = $this->crumb[0];
        } else {
            $parentClassInfo = $parentClassInfo[0];
        }
        $topClassInfo = $this->crumb[0];

        if($this->classInfo['tpl_content']) {
            $this->tpl = $this->classInfo['tpl_content'];
        }

        return $this->run([
            'classInfo' => $this->classInfo,
            'parentClassInfo' => $parentClassInfo,
            'topClassInfo' => $topClassInfo,
        ]);
    }

    protected function data() {
        $this->info = $this->getInfo();

        if(empty($this->info) || !$this->info['status']) {
            return $this->stop('文章不存在！', 404);
        }

        if($this->info['tpl']) {
            $this->tpl = $this->info['tpl'];
        }
        target('article/Article')->where(['article_id' => $this->info['article_id']])->setInc('view');

        $tagList = [];
        if($this->info['tags_id']) {
            $tagList = target('site/SiteTags')->contentTags('article', $this->info['tags_id']);
        }
        $this->info['content'] = html_out($this->info['content']);

        target('statis/Views', 'service')->statis([
            'has_id' => $this->info['article_id'],
            'species' => 'article',
        ]);

        return $this->run([
            'info' => $this->info,
            'tagList' => $tagList,
            'tpl' => $this->tpl
        ]);
    }

}