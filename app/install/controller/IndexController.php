<?php

namespace app\install\controller;
set_time_limit(0);

/**
 * 安装模块
 */
class IndexController extends \dux\kernel\Controller {

    private $msgLog = array();
    private $lock = '';
    private $error = false;

    public function __construct() {
        $this->lock = ROOT_PATH . 'install.lock';
        if (is_file($this->lock)) {
            $this->redirect('/');
        }
    }

    /**
     * 主页
     */
    public function index() {
        $this->display();
    }

    /**
     * 安装环境检测
     */
    public function detect() {
        $dirList = array(
            'data/cache',
            'data/config',
            'data/log',
            'upload',
        );
        $list = [];
        foreach ($dirList as $file) {
            $writeable = is_writeable(ROOT_PATH . $file);
            $list[] = array(
                "dir" => $file,
                "writeable" => $writeable
            );
        }
        $funList = [
            [
                'name' => 'Mbstring字符处理',
                'func' => 1,
                'value' => 'mb_substr',
                'must' => 1
            ],
            [
                'name' => 'Curl传输',
                'func' => 1,
                'value' => 'curl_init',
                'must' => 1
            ],
            [
                'name' => 'Gd图像处理',
                'func' => 1,
                'value' => 'imagecreate',
                'must' => 1
            ],
            [
                'name' => 'Gzip压缩',
                'func' => 1,
                'value' => 'ob_gzhandler',
                'must' => 1
            ],
            [
                'name' => 'Pdo数据库',
                'func' => 0,
                'value' => 'Pdo',
                'must' => 1
            ],
            [
                'name' => 'Mcrypt加解密',
                'func' => 1,
                'value' => 'mcrypt_encrypt'
            ],
            [
                'name' => 'Redis数据库',
                'func' => 0,
                'value' => 'Redis',
            ],
        ];

        foreach ($funList as $key => $vo) {
            if($vo['func']) {
                $status = function_exists($vo['value']);
            }else{
                $status = class_exists($vo['value']);
            }
            $funList[$key]['status'] = $status;
        }

        $this->assign('list', $list);
        $this->assign('funList', $funList);
        $this->display();
    }

    /**
     * 配置系统
     */
    public function config() {
        $this->display();
    }

    public function install() {
        $this->assign('msgLog', $this->msgLog);
        $this->assign('error', $this->error);
        ob_end_clean();
        ob_implicit_flush();
        header('X-Accel-Buffering: no');
        $this->display();
        //检测信息
        $data = request('post');
        if (!$data['host']) {
            $this->msg('请填写数据库地址！', true);
        }
        if (!$data['port']) {
            $this->msg('请填写数据库端口！', true);
        }
        if (!$data['dbname']) {
            $this->msg('请填写数据库名称！', true);
        }
        if (!$data['username']) {
            $this->msg('请填写数据库用户名！', true);
        }
        if (!$data['prefix']) {
            $this->msg('请填写数据表前缀！', true);
        }
        if (!$data['admin_user']) {
            $this->msg('管理员账号不能为空！', true);
        }
        if (!$data['admin_pw']) {
            $this->msg('管理员密码不能为空！', true);
        }
        if(!class_exists('Pdo')){
            $this->msg('安装失败，请确保您的环境支持pdo扩展！', true);
        }
        if($this->error) {
            $this->stop();
        }
        $this->msg('安装信息验证成功!');
        $dsn = "mysql:host=" . $data['host'] . ";port=" . $data['port'] . ";dbname=" . $data['dbname']. ';charset=utf8';

        $link = null;
        try {
            $link = new \PDO($dsn, $data['username'], $data['password']);
        } catch (\PDOException $e) {
            $this->msg('数据库连接失败，请检查连接信息是否正确或者数据库是否存在！错误信息:' . $e->getMessage(), true);
            $this->stop();
        }
        if (!$link) {
            $this->msg('数据库连接失败，请检查连接信息是否正确或者数据库是否存在！', true);
            $this->stop();
        }
        $link->exec("SET NAMES UTF-8");
        $this->msg('数据库检查完成...');

        //修改数据库文件
        $file = 'data/config/use/db';

        $config['type'] = 'mysql';
        $config['host'] = $data['host'];
        $config['port'] = $data['port'];
        $config['dbname'] = $data['dbname'];
        $config['username'] = $data['username'];
        $config['password'] = $data['password'];
        $config['prefix'] = $data['prefix'];

        $conf = load_config($file);

        $dbConfig = array_merge($conf['dux.use_data'], $config);
        $status = save_config($file,  [
            'dux.use_data' => $dbConfig
        ]);
        \dux\Config::set('dux.database', ['default' => $dbConfig]);
        if ($status) {
            $this->msg('配置数据库信息完成...');
        } else {
            $this->msg('配置数据库信息失败！', true);
            $this->stop();
        }
        $file = APP_PATH . 'install/data/install.sql';
        if (!is_file($file)) {
            $this->msg('数据库文件不存在');
        }
        $sqlData = \dux\lib\Install::mysql($file, 'dux_', $data['prefix']);

        $password = md5($data['admin_pw']);
        $sqlData[] = "update `{$data['prefix']}system_user` set username='{$data['admin_user']}',password='{$password}' where user_id=1;";

        foreach ($sqlData as $sql) {
            $rst = $link->exec($sql);
            if ($rst === false) {
                $this->msg('数据库文件执行失败,请清空表后重试!');
            }
        }
        $this->msg('创建基础数据库完成...');


        $file = 'data/config/use/use';
        $config = [
            'safe_key' => $this->getCode(20),
            'cookie_pre' => $this->getCode(4) . '_',
            'com_key' => $this->getCode(32)
        ];

        $conf = load_config($file);

        $status = save_config($file,  [
            'dux.use' => array_merge($conf['dux.use'], $config)
        ]);
        if ($status) {
            $this->msg('配置站点安全信息完成...');
        } else {
            $this->msg('配置站点安全信息失败！', true);
            $this->stop();
        }
        file_put_contents($this->lock, time());

        $this->complete('安装程序执行完毕！请删除install应用');
        $this->stop();

    }

    private function msg($msg, $error = false) {
        sleep(1);
        echo "<script>msg(\"$msg\", $error);</script>";
    }

    protected function complete($msg) {
        echo "<script>complete(\"$msg\");</script>";
    }

    private function stop() {
        exit;
    }

    public function getCode($length = 5) {
        $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $result = '';
        $l = strlen($str) - 1;
        $num = 0;
        for ($i = 0; $i < $length; $i++) {
            $num = rand(0, $l);
            $a = $str[$num];
            $result = $result . $a;
        }
        return $result;
    }
}