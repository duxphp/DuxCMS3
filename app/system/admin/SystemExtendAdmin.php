<?php
/**
 * 扩展系统控制器
 */

namespace app\system\admin;


class SystemExtendAdmin extends \app\system\admin\SystemAdmin {

    public function __construct() {
        parent::__construct();
    }

    protected $_model;

    /**
     * 列表
     */
    public function index() {
        if (!$this->infoModule['fun']['index']) {
            $this->error404();
        }
        //获取URL参数
        $pageMaps = [];
        $whereMaps = [];
        if (method_exists($this, '_indexParam')) {
            $indexParam = (array)$this->_indexParam();
            $pageParams = request();
            if (!empty($indexParam)) {
                foreach ($indexParam as $key => $val) {
                    $value = urldecode($pageParams[$key]);
                    $value = \dux\lib\Str::htmlClear($value);
                    if ($value === '') {
                        continue;
                    }
                    $pageMaps[$key] = $value;
                    if ($key == 'keyword') {
                        $vals = explode(',', $val);
                        $sql = [];
                        foreach ($vals as $k) {
                            $sql[] = "({$k} like '%{$value}%')";
                        }
                        if (empty($sql)) {
                            continue;
                        }
                        $whereMaps['_sql'][] = '(' . implode(' OR ', $sql) . ')';
                    } else {
                        $whereMaps[$val] = $value;
                    }
                }
            }
        }
        $whereMaps = array_filter($whereMaps, function ($v) {
            if ($v === "") {
                return false;
            }
            return true;
        });
        //生成筛选条件
        if (method_exists($this, '_indexWhere')) {
            $where = (array)$this->_indexWhere($whereMaps);
        } else {
            $where = $whereMaps;
        }
        //生成页面变量
        if (method_exists($this, '_indexMaps')) {
            $pageMaps = (array)$this->_indexMaps($pageMaps);
        }
        //生成分页数量
        if (method_exists($this, '_indexPage')) {
            $pageLimit = $this->_indexPage($whereMaps);
        } else {
            $pageLimit = 20;
        }

        //生成列表排序
        if (method_exists($this, '_indexOrder')) {
            $order = $this->_indexOrder($whereMaps);
        } else {
            $order = null;
        }

        if (method_exists($this, '_indexCount')) {
            $count = $this->_indexCount($where);
        } else {
            $count = target($this->_model)->countList($where);
        }

        $pageData = $this->pageData($count, $pageLimit, $pageMaps);

        //生成分页数据
        if (method_exists($this, '_indexData')) {
            $list = $this->_indexData($where, $pageData['limit'], $order);
        } else {
            $list = target($this->_model)->loadList($where, $pageData['limit'], $order);
        }
        //基础赋值
        $this->assign('pri', target($this->_model)->getPrimary());
        $this->assign('list', $list);
        $this->assign('page', $pageData['html']);
        $this->assign('pageMaps', $pageMaps);

        //模板赋值
        if (method_exists($this, '_indexAssign')) {
            foreach ($this->_indexAssign($pageMaps, $where) as $key => $value) {
                $this->assign($key, $value);
            }
        }
        $tpl = '';
        if (method_exists($this, '_indexTpl')) {
            $tpl = $this->_indexTpl();
        }
        $this->systemDisplay($tpl);
    }

    /**
     * 添加
     */
    public function add() {
        if (!$this->infoModule['fun']['add']) {
            $this->error404();
        }
        if (!isPost()) {
            //逻辑判断
            if (method_exists($this, '_addLogic')) {
                $this->_addLogic();
            }
            //模板赋值
            if (method_exists($this, '_addAssign')) {
                foreach ($this->_addAssign() as $key => $value) {
                    $this->assign($key, $value);
                }
            }
            //被动接口
            $hookList = run('service', APP_NAME, 'admin' . MODULE_NAME . 'Html');
            $hookHtml = [];
            foreach ($hookList as $app => $vo) {
                if (!empty($vo)) {
                    $hookHtml[$app] = $vo;
                }
            }
            $hookHtml = array_sort($hookHtml, 'sort');
            $this->assign('hookHtml', $hookHtml);

            $this->assign('pri', target($this->_model)->getPrimary());
            $this->assign('assignName', '增加');

            if (method_exists($this, '_addTpl')) {
                $tpl = $this->_addTpl();
            } else {
                $tpl = 'info';
            }
            $this->systemDisplay($tpl);
        } else {
            $data = [];
            //添加前处理
            if (method_exists($this, '_addBefore')) {
                $data = $this->_addBefore();
            }
            //添加
            $id = target($this->_model)->saveData('add', $data);
            if ($id) {
                //添加后处理
                if (method_exists($this, '_addAfter')) {
                    $this->_addAfter($id);
                }
                if (method_exists($this, '_saveAfter')) {
                    $this->_saveAfter($id);
                }
                //被动接口
                $data[target($this->_model)->getPrimary()] = $id;
                $hookList = run('service', APP_NAME, 'admin' . MODULE_NAME . 'Save', [
                    'id' => $id
                ]);
                if (!empty($hookList)) {
                    foreach ($hookList as $app => $vo) {
                        if (!$vo) {
                            $this->error(target($app . '/' . APP_NAME, 'service')->getError());
                        }
                    }
                }
                if (method_exists($this, '_indexUrl')) {
                    $url = $this->_indexUrl($id);
                } else {
                    $url = url('index');
                }
                $this->success('添加成功！', $url);
            } else {
                $msg = target($this->_model)->getError();
                if (empty($msg)) {
                    $this->error('添加失败');
                } else {
                    $this->error($msg);
                }
            }
        }
    }

    /**
     * 通用编辑
     */
    public function edit() {
        //判断编辑
        if (!$this->infoModule['fun']['edit']) {
            $this->error404();
        }
        if (!isPost()) {
            $id = request('get', 'id', 0, 'intval');
            if (empty($id)) {
                $this->error('参数不能为空！');
            }
            //获取信息
            if (method_exists($this, '_editInfo')) {
                $info = $this->_editInfo($id);
            } else {
                $model = target($this->_model);
                $info = $model->getInfo($id);
                if (!$info) {
                    $this->error('该条记录不存在');
                }
            }
            //逻辑判断
            if (method_exists($this, '_editLogic')) {
                $this->_editLogic($info);
            }
            //模板赋值
            $this->assign('info', $info);
            if (method_exists($this, '_editAssign')) {
                foreach ($this->_editAssign($info) as $key => $value) {
                    $this->assign($key, $value);
                }
            }
            //被动接口
            $hookList = run('service', APP_NAME, 'admin' . MODULE_NAME . 'Html', [
                'id' => $info[target($this->_model)->getPrimary()]
            ]);
            $hookHtml = [];
            foreach ($hookList as $app => $vo) {
                if (!empty($vo)) {
                    $hookHtml[$app] = $vo;
                }
            }
            $hookHtml = array_sort($hookHtml, 'sort');
            $this->assign('hookHtml', $hookHtml);

            $this->assign('pri', target($this->_model)->getPrimary());
            $this->assign('assignName', '编辑');

            if (method_exists($this, '_editTpl')) {
                $tpl = $this->_editTpl();
            } else {
                $tpl = 'info';
            }
            $this->systemDisplay($tpl);
        } else {
            //编辑前处理
            if (method_exists($this, '_editBefore')) {
                $data = $this->_editBefore();
            }
            if (target($this->_model)->saveData('edit', $data)) {
                //编辑后处理
                if (method_exists($this, '_editAfter')) {
                    $this->_editAfter($data);
                }
                if (method_exists($this, '_saveAfter')) {
                    $this->_saveAfter($data[target($this->_model)->getPrimary()]);
                }
                if (method_exists($this, '_indexUrl')) {
                    $url = $this->_indexUrl($data[target($this->_model)->getPrimary()]);
                } else {
                    $url = url('index');
                }
                //被动接口
                $hookList = run('service', APP_NAME, 'admin' . MODULE_NAME . 'Save', [
                    'id' => request('', target($this->_model)->getPrimary())
                ]);
                if (!empty($hookList)) {
                    foreach ($hookList as $app => $vo) {
                        if (!$vo) {
                            $this->error(target($app . '/' . APP_NAME, 'service')->getError());
                        }
                    }
                }
                $this->success('修改成功！', $url);
            } else {
                $msg = target($this->_model)->getError();
                if (empty($msg)) {
                    $this->error('修改失败');
                } else {
                    $this->error($msg);
                }
            }
        }
    }

    /**
     * 修改状态
     */
    public function status() {
        if (!$this->infoModule['fun']['status']) {
            $this->error404();
        }
        $id = request('post', 'id', 0, 'intval');
        $status = request('post', 'status', 0);
        $primary = target($this->_model)->getPrimary();
        if (!$primary || !$id) {
            $this->error('参数传递错误！');
        }
        $where = [];
        $where[$primary] = $id;
        $data = [];
        $data['status'] = $status;
        if (method_exists($this, '_statusData')) {
            $status = $this->_statusData($id, $status);
        } else {
            $status = target($this->_model)->where($where)->data($data)->update();
        }
        if (!$status) {
            $this->error('状态更改失败,请重试！');
        }
        $this->success('状态更改成功！');
    }

    /**
     * 通用删除
     */
    public function del() {
        //判断删除
        if (!$this->infoModule['fun']['del']) {
            $this->error404();
        }
        $id = request('post', 'id', 0, 'intval');
        if (empty($id)) {
            $this->error('ID不能为空！');
        }
        //删除前动作
        if (method_exists($this, '_delBefore')) {
            $this->_delBefore($id);
        }
        if (target($this->_model)->delData($id)) {
            //删除后处理
            if (method_exists($this, '_delAfter')) {
                $this->_delAfter($id);
            }
            //被动接口
            $hookList = run('service', APP_NAME, 'admin' . MODULE_NAME . 'Del', [
                'id' => $id
            ]);
            if (!empty($hookList)) {
                foreach ($hookList as $app => $vo) {
                    if (!$vo) {
                        $this->error(target($app . '/' . APP_NAME, 'service')->getError());
                    }
                }
            }
        } else {
            $msg = target($this->_model)->getError();
            if (empty($msg)) {
                $this->error('删除失败！');
            } else {
                $this->error($msg);
            }
        }
        $this->success('删除成功！');
    }

    /**
     * 分页处理
     */
    public function pageData($sumLimit, $pageLimit, $params = []) {
        $pageObj = new \dux\lib\Pagination($sumLimit, request('get', 'page', 1), $pageLimit);
        $pageData = $pageObj->build();
        $limit = [$pageData['offset'], $pageLimit];
        $pageData['prevUrl'] = $this->createPageUrl($pageData['prev']);
        $pageData['nextUrl'] = $this->createPageUrl($pageData['next']);
        $html = '<div class="foot-pages">
                <span>共'.$pageData['count'].'条 '.$pageData['page'].'页</span>
                <a href="{prevUrl}"> <  上一页</a>';
        foreach ($pageData['pageList'] as $vo) {
            if ($vo == $pageData['current']) {
                $html .= '<span class="current">' . $vo . '</span>';
            } else {
                $html .= '<a href="' . $this->createPageUrl($vo, $params) . '">' . $vo . '</a>';
            }
        }
        $html .= '<a href="{nextUrl}">下一页  > </a>
            <form name="pages"><input class="keyword" name="page" type="text" value="'.$pageData['current'].'"><a href="javascript:document.pages.submit();">跳转</a></form>
            </div>';

        foreach ($pageData as $key => $vo) {
            $html = str_replace('{' . $key . '}', $vo, $html);
        }

        return [
            'html' => $html,
            'limit' => $limit,
        ];
    }

    /**
     * 生成分页URL
     */
    protected function createPageUrl($page = 1) {
        $url = $_SERVER['REQUEST_URI'];
        $preg = '/page\=([0-9]*)/';
        if (preg_match($preg, $url)) {
            $url = preg_replace($preg, 'page=' . $page, $url);
        } else {
            if (stristr($url, '?') === false) {
                $url = $url . '?page=' . $page;
            } else {
                $url = $url . '&page=' . $page;
            }
        }

        return $url;
    }


}