<?php

namespace app\system\service;

use function GuzzleHttp\json_decode;

class ComService extends \app\base\service\BaseService {

    private $header = 'X-REQUESTED-WITH: XMLHTTPREQUEST';
    private $updateUrl = 'https://www.duxphp.com';
    private $label = 'duxcms';

    /**
     * 版本信息
     *
     * @return void
     */
    public function info() {
        $verInfo = \dux\Config::get('dux.use_ver');
        $data = \dux\lib\Http::doPost($this->updateUrl . '/service/Update/info', [
            'label' => $this->label,
            'ver' => $verInfo['ver'],
            'date' => $verInfo['date'],
            'release' => $verInfo['release'],
        ], 10, $this->header);
        if (empty($data)) {
            return $this->error('内部请求失败！');
        }
        $data = \json_decode($data, true);
        if (empty($data)) {
            return $this->error('服务器返回失败');
        }
        if ($data['code'] != 200) {
            return $this->error($data['message']);
        }
        return $this->success($data['message']);
    }

    /**
     * 升级检查
     *
     * @return void
     */
    public function check() {
        $verInfo = \dux\Config::get('dux.use_ver');
        $updateConfig = \dux\Config::get('dux.update');
        $data = \dux\lib\Http::doPost($this->updateUrl . '/service/Update/check', [
            'label' => $this->label,
            'ver' => $verInfo['ver'],
            'date' => $verInfo['date'],
            'release' => intval($updateConfig['release']),
        ], 10, $this->header);
        if (empty($data)) {
            return $this->error('内部请求失败！');
        }
        $data = \json_decode($data, true);
        if (empty($data)) {
            return $this->error('服务器返回失败');
        }
        if ($data['code'] != 200) {
            return $this->error($data['message']);
        }
        return $this->success($data['message']);
    }

    /**
     * 获取更新内容
     *
     * @return void
     */
    public function getUpdate($verId, $list) {
        $verInfo = \dux\Config::get('dux.use_ver');
        $data = \dux\lib\Http::doPost($this->updateUrl . '/service/Update/package', [
            'id' => $verId,
            'ver' => $verInfo['ver'],
            'list' => $list,
        ], 20, $this->header);
        if (empty($data)) {
            return $this->error('更新服务器请求失败！');
        }
        $data = \json_decode($data, true);
        if (empty($data)) {
            return $this->error('更新服务器返回失败');
        }
        if ($data['code'] != 200) {
            return $this->error($data['message']);
        }
        return $this->success($data['message']);
    }

    /**
     * 下载更新包
     *
     * @return void
     */
    public function downloadUpdate($file) {
        $data = \dux\lib\Http::doGet($file);
        if (empty($data)) {
            return $this->error('更新包下载失败！');
        }
        $dir = ROOT_PATH . 'data/update/';
        del_dir($dir);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0755, true)) {
                return $this->error('[data/update/]目录没有权限！');
            }
        }
        $zipFile = $dir . 'tmp.zip';
        $file = fopen($zipFile, "w+");
        if (!$file) {
            return $this->error('保存更新包失败！');
        }
        if (!fputs($file, $data)) {
            return $this->error('获取更新包内容失败！');
        }
        fclose($file);
        $zip = new \PhpZip\ZipFile();
        if ($zip->openFile($zipFile)->extractTo($dir . 'tmp')) {
            $zip->close();
        } else {
            $this->error('解压更新包失败！');
        }
        return $this->success($dir . 'tmp');

    }

    /**
     * 文件对比
     *
     * @return void
     */
    public function diff($verId, $list) {
        $data = \dux\lib\Http::doPost($this->updateUrl . '/service/Update/diff', [
            'id' => $verId,
            'list' => $list,
        ], 20, $this->header);
        if (empty($data)) {
            return $this->error('内部请求失败！');
        }
        $data = \json_decode($data, true);
        if (empty($data)) {
            return $this->error('服务器返回失败');
        }
        if ($data['code'] != 200) {
            return $this->error($data['message']);
        }
        return $this->success($data['message']);
    }

    /**
     * 获取文件编码
     *
     * @return void
     */
    public function getMd5() {
        $data = $this->getFiles(ROOT_PATH, [
            ROOT_PATH . '.git',
            ROOT_PATH . 'data',
            ROOT_PATH . 'upload',
            ROOT_PATH . 'vendor',
            ROOT_PATH . 'public/fonts',
        ], [
            '.DS_Store',
        ]);
        return $data;
    }

    private function getFiles($path, $excludeDir = [], $excludeFiles = []) {
        if (!file_exists($path)) {
            return [];
        }
        $handle = opendir($path);
        $fileItem = [];
        if ($handle) {
            while (($file = readdir($handle)) !== false) {
                $newPath = realpath($path . DIRECTORY_SEPARATOR . $file);
                if (in_array($newPath, $excludeDir)) {
                    continue;
                }
                if (in_array($file, $excludeFiles)) {
                    continue;
                }
                if (is_dir($newPath) && $file != '.' && $file != '..') {
                    $fileItem = array_merge($fileItem, $this->getFiles($newPath, $excludeDir, $excludeFiles));
                } else if (is_file($newPath)) {
                    $fileItem[$newPath] = md5_file($newPath);
                }
            }
        }
        @closedir($handle);
        return $fileItem;
    }
}