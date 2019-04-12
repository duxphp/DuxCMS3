<?php

/**
 * 系统首页
 */

namespace app\system\admin;

class IndexAdmin extends \app\system\admin\SystemAdmin {

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '系统首页',
                'description' => '系统基本信息参数',
            ],
        ];
    }

    /**
     * 首页
     */
    public function index() {

        target('system/Statistics', 'service')->refreshStats();

        $siteViews = target('statis/StatisViews')->where([
            'species' => 'site',
        ])->sum('num');
        $this->assign('siteViews', $siteViews);

        $systemStatis = target('system/SystemStatistics')->countStats();
        $this->assign('systemStatis', $systemStatis);

        $startTime = date('Y-m-d 0:0:0', strtotime("-30 day"));
        $stopTime = date('Y-m-d  H:i:s');

        if ($startTime) {
            $startTime = date('Ymd', strtotime($startTime));
        }
        if ($stopTime) {
            $stopTime = date('Ymd', strtotime($stopTime));
        }

        $statsLabel = [];
        for ($i = strtotime($startTime); $i <= strtotime($stopTime); $i += 86400) {
            $statsLabel[] = date('Y-m-d', $i);
        }

        $data = target('statis/StatisViews')->query("select date,sum(num) as view_sum  from `{pre}statis_views` where species = 'site' and date >= " . $startTime . " and date <= " . $stopTime . " group by date");
        $listViewData = [];
        foreach ($data as $vo) {
            $listViewData[date('Y-m-d', strtotime($vo['date']))] += $vo['view_sum'];
        }

        $viewData = [];
        foreach ($statsLabel as $vo) {
            if ($listViewData[$vo]) {
                $viewData[] = $listViewData[$vo];
            } else {
                $viewData[] = 0;
            }
        }

        $viewBarJs = target('tools/Echarts', 'service')->bar('order-bar', $statsLabel, [
            [
                'name' => '站点访问量',
                'data' => $viewData,
            ],
        ], 400);

        $config = \dux\Config::get('dux.use');
        $cache = \dux\Dux::cache($config['data_cache']);
        $updateConfig = \dux\Config::get('dux.update');
        $updateStatus = false;
        if (!$cache->get('update.status')) {
            $updateStatus = true;
            $cache->set('update.status', time(), $updateConfig['interval'] * 86400);
        }
        
        $this->assign('viewBarJs', $viewBarJs);
        $this->assign('ver', \dux\Config::get('dux.use_ver'));
        $this->assign('updateStatus', $updateStatus);
        $this->systemDisplay();
    }

    public function update() {
        header('X-Accel-Buffering: no');
        ob_end_clean();
        ob_implicit_flush();
        $this->systemDisplay();
        $updateConfig = \dux\Config::get('dux.update');
        if (!$updateConfig['status']) {
            $this->updateMsg('系统已关闭更新，请联系管理员！', true);
        }
        $varInfo = \dux\Config::get('dux.use_ver');
        $this->updateMsg('当前版本：' . $varInfo['ver'] . ' ' . ($varInfo['release'] ? '正式版' : '预览版') . ' [' . $varInfo['date'] . ']');
        $this->updateMsg('获取更新信息中,请稍等...');
        $info = target('system/Com', 'service')->check();
        if ($info) {
            $this->updateMsg('检测到新版本：' . $info['name']);
        } else {
            $this->updateMsg(target('system/Com', 'service')->getError(), true);
        }
        $this->updateMsg('获取本地文件编码...');
        $data = target('system/Com', 'service')->getMd5();
        $localData = [];
        foreach ($data as $key => $vo) {
            $key = str_replace(ROOT_PATH, '', $key);
            $localData[$key] = $vo;
        }
        $this->updateMsg('获取差异文件...');
        $diff = target('system/Com', 'service')->diff($info['ver_id'], $localData);
        if (!$diff) {
            $this->updateMsg(target('system/Com', 'service')->getError(), true);
        }
        $diffList = \json_decode($diff, true);
        $this->updateMsg('获取到' . count($diffList) . '个差异文件');
        foreach ($diffList as $key => $vo) {
            $this->updateMsg('检测到更新文件 [' . $key . ']');
        }
        $this->updateMsg('开始从更新服务器获取更新...');
        $file = target('system/Com', 'service')->getUpdate($info['ver_id'], $localData);
        if (!$file) {
            $this->updateMsg(target('system/Com', 'service')->getError(), true);
        }
        $this->updateMsg('获取到更新包，开始下载更新...');
        $dir = target('system/Com', 'service')->downloadUpdate($file['url']);
        if (!$dir) {
            $this->updateMsg(target('system/Com', 'service')->getError(), true);
        }
        $this->updateMsg('更新包下载成功，开始更新文件...');
        if (!copy_dir($dir, ROOT_PATH)) {
            $this->updateMsg('更新文件失败，请设置系统目录权限！');
        }
        $this->updateMsg('更新文件成功，清理临时文件...');
        del_dir(ROOT_PATH . 'data/update');

        if (file_exists(ROOT_PATH . $file['md5'] . '.php')) {
            $this->updateMsg('开始执行升级脚本...');
            require_once ROOT_PATH . $file['md5'] . '.php';
            $class = new \DuxUpdate();
            if (!$class->run()) {
                @unlink(ROOT_PATH . $file['md5'] . '.php');
                $this->updateMsg('升级脚本执行失败！', true);
            }
        }
        if (file_exists(ROOT_PATH . $file['md5'] . '.sql')) {
            $this->updateMsg('升级脚本成功，开始执行数据升级...');
            $dbConfig = \dux\Config::get('dux.use_data');
            $sqlData = \dux\lib\Install::mysql(ROOT_PATH . $file['md5'] . '.sql', 'dux_', $dbConfig['prefix']);
            if ($sqlData) {
                foreach ($sqlData as $sql) {
                    $rst = target('base/Base')->execute($sql);
                    if (!$rst) {
                        @unlink(ROOT_PATH . $file['md5'] . '.sql');
                        $this->updateMsg('数据库升级失败！', true);
                    }
                }
            }
        }
        $this->updateMsg('开始清理临时脚本...');
        if (file_exists(ROOT_PATH . $file['md5'] . '.php')) {
            @unlink(ROOT_PATH . $file['md5'] . '.php');
        }
        if (file_exists(ROOT_PATH . $file['md5'] . '.sql')) {
            @unlink(ROOT_PATH . $file['md5'] . '.sql');
        }
        $this->updateMsg('更新当前版本号...');

        $status = save_config('data/config/com/ver', [
            'dux.use_ver' => array_merge($varInfo, [
                'ver' => $info['ver'],
                'date' => date('Ymd', $info['date']),
                'release' => intval($info['release']),
            ]),
        ]);
        if (!$status) {
            $this->updateMsg('更新版本号失败...', true);
        }

        $this->complete('升级系统成功，当前系统已更新至 ' . $info['name']);
    }

    private function updateMsg($msg, $error = false) {
        usleep(50000);
        if ($error) {
            exit("<script>msg(\"$msg\", $error);</script>");
        } else {
            echo "<script>msg(\"$msg\", $error);</script>";
        }
    }

    private function complete($msg) {
        echo "<script>complete(\"$msg\");</script>";
    }

    public function info() {
        $info = target('system/Com', 'service')->info();
        if ($info) {
            $this->success($info);
        } else {
            $this->error(target('system/Com', 'service')->getError());
        }
    }

    public function updateCheck() {
        $info = target('system/Com', 'service')->check();
        if ($info) {
            $this->success($info);
        } else {
            $this->error(target('system/Com', 'service')->getError());
        }
    }

}