<?php

/**
 * APP信息
 */
namespace app\system\model;

class SystemApplicationModel {

    private $count = 0;
    private $error = '';

    /**
     * 获取应用列表
     */
    public function loadList() {
        $list = glob(APP_PATH . '*/config/config.php');
        $configArray = array();
        foreach ($list as $file) {
            $this->count++;
            $file = str_replace('\\', '/', $file);
            $fileName = str_replace(APP_PATH, '', $file);
            $fileName = explode('/', $fileName);
            $fileName = $fileName[0];
            $info = $this->getInfo($fileName);
            $configArray[$info['app']] = $info;
        }
        return $configArray;
    }

    /**
     * 应用数量
     * @return int
     */
    public function countList() {
        return $this->count;
    }

    /**
     * 应用信息
     * @param $app
     * @return array|mixed
     */
    public function getInfo($app) {
        $info = load_config('app/' . $app . '/config/config');
        if (empty($info)) {
            return array();
        }
        $info['app'] = $app;
        if ($info['app.system']) {
            $info['app.state'] = 1;
            $info['app.install'] = 1;
        }
        return $info;
    }

    /**
     * 应用ID列表
     * @return array
     */
    public function getIds() {
        $appList = $this->loadList();
        $appIds = array();
        foreach ($appList as $vo) {
            $appIds[] = $vo['app.id'];
        }
        return $appIds;
    }

    /**
     * 检测安装信息
     * @param $app
     * @return bool
     */
    public function appDetect($app) {
        $info = $this->getInfo($app);
        if (empty($info)) {
            $this->error = '未发现应用信息，请检查应用！';
            return false;
        }
        $relyList = explode(',', $info['app.rely']);
        if (empty($relyList)) {
            return true;
        }
        $appIds = $this->getIds();
        if (in_array($info['app.id'], $appIds)) {
            $this->error = '该应用已安装，请勿重复安装！';
            return false;
        }
        foreach ($relyList as $vo) {
            if (!in_array($vo, $appIds)) {
                $this->error = '缺少应用：' . $info['app.rely.name'];
                return false;
            }
        }
        return true;
    }

    /**
     * 执行应用数据
     * @param $app
     * @return bool
     */
    public function appSql($app, $install = 1) {
        $info = $this->getInfo($app);
        if ($install) {
            $file = 'install';
        } else {
            $file = 'uninstall';
        }
        $sqlFile = APP_PATH . $app . '/data/' . $file . '.sql';
        if (!is_file($sqlFile)) {
            return true;
        }
        $config = \dux\Config::get('dux.database');
        $install = new \dux\lib\Install();
        $sqlList = $install->mysql($sqlFile, $info['app.prefix'], $config['prefix']);
        $model = target('system/SystemFile');
        $model->beginTransaction();
        foreach ($sqlList as $sql) {
            if ($model->execute($sql) === false) {
                $this->error = '应用数据执行失败，请重新安装！';
                return false;
            }
        }
        $model->commit();
        return true;
    }

    /**
     * 配置应用信息
     * @param $app
     * @return bool
     */
    public function appConfig($app, $install = 1) {
        if ($install) {
            $config = [
                'app.install' => 1,
                'app.state' => 1
            ];
        } else {
            $config = [
                'app.install' => 0,
                'app.state' => 0
            ];
        }
        $status = save_config('app/' . $app . '/config/config', $config);
        if (!$status) {
            $this->error = '应用状态更改失败,请手动更改应用配置!';
            return false;
        }
        return true;
    }

    /**
     * 配置应用信息
     * @param $app
     * @return bool
     */
    public function appStatus($app, $status = 1) {
        if ($status) {
            $config = [
                'app.state' => 1
            ];
        } else {
            $config = [
                'app.state' => 0
            ];
        }
        $status = save_config('app/' . $app . '/config/config', $config);
        if (!$status) {
            $this->error = '应用状态更改失败,请手动更改应用配置!';
            return false;
        }
        return true;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError() {
        return $this->error;
    }

}
