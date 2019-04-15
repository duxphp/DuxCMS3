<?php

/**
 * 生成Api文档
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;

class ApiAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'toolsQueue';

    private $docsDir = '';

    public function __construct() {
        parent::__construct();
        $config = target('site/SiteConfig')->getConfig();
        if(!empty($config['tools_apis'])) {
            $this->docsDir = ROOT_PATH . $config['tools_apis'] . '//';
        }
    }

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => 'Api文档',
                'description' => '管理生成Api文档',
            ],
            'fun' => [
                'index' => true,
                'del' => true,
            ],
        ];
    }

    public function index() {
        $file = $this->docsDir . 'README.md';
        $config = target('site/SiteConfig')->getConfig();
        if (!isPost()) {
            $content = '';
            if (is_file($file) && !empty($config['tools_apis'])) {
                $content = file_get_contents($file);
            } else {
                $content = file_get_contents(ROOT_PATH . 'app/tools/view/tpl/api/README.md');
            }
            $this->assign('content', $content);
            $this->assign('config', $config);
            $this->systemDisplay();
        } else {
            if (empty($config['tools_apis'])) {
                $this->error('请先设置文档目录');
            }
            if(!$this->isDir()) {
                $this->error('目录写入失败！');
            }
            $content = $_POST['content'];
            if (!file_put_contents($file, $content)) {
                $this->error('页面内容无法写入，请检查是否有权限！');
            }
            $this->success('写入成功!');
        }
    }

    public function config() {
        if (target('site/SiteConfig')->saveInfo()) {
            if(empty($_POST['tools_apis'])) {
                $this->error('请设置文档目录');
            }
            $fileDir = ROOT_PATH . $_POST['tools_apis'] . '/';
            $this->docsDir = $fileDir;
            if(!$this->isDir()) {
                $this->error('目录写入失败！');
            }
            $this->success('站点配置成功！');
        } else {
            $this->error('站点配置失败');
        }
    }

    public function make() {
        header('X-Accel-Buffering: no');
        ob_end_clean();
        ob_implicit_flush();
        $this->systemDisplay();
        if(empty($this->docsDir)) {
            $this->tip('请先设置文档目录', true);
        }

        $this->tip('检查文档目录...');
        if (!$this->isDir()) {
            $this->tip('目录写入失败!', true);
        }
        $this->tip('获取系统Api中...');
        $data = $this->getApi();
        $this->tip('获取Api信息完成...');
        $this->tip('开始生成文档目录...');
        $this->makeDirectory($data);
        $this->tip('开始生成文档...');
        $this->makePage($data);
        $this->complete('文档生成结束，请直接通过域名访问 api 文档');
    }

    private function isDir() {
        if(!is_dir($this->docsDir)) {
            if (@mkdir($this->docsDir, 0755, true) === false) {
                return false;
            }
            copy_dir(ROOT_PATH . 'app/tools/view/tpl/api/', $this->docsDir);
        }
        return true;
    }

    private function tip($msg, $error = false) {
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

    protected function makePage($data) {

        foreach ($data as $key => $vo) {
            if (!is_dir($this->docsDir . $key)) {
                if (@mkdir($this->docsDir . $key, 0755, true) === false) {
                    $this->tip('docs目录没有写入权限!', true);
                }
            }
            foreach ($vo['class'] as $class) {
                foreach ($class['methods'] as $methods) {

                    $md = [];
                    $md[] = '# ' . $methods['name'];
                    $md[] = '';
                    if ($methods['desc']) {
                        $md[] = $methods['desc'];
                    }
                    $md[] = '';
                    $md[] = '* 请求地址: `' . $methods['url'] . '`';
                    $md[] = '* 请求方式: `' . $methods['method'] . '`';
                    $md[] = '';
                    if ($methods['param']) {
                        $md[] = '* 请求参数';
                        $md[] = '';
                        $md[] = '|字段|类型|说明|';
                        $md[] = '|---|---|---|';
                        foreach ($methods['param'] as $vo) {
                            $md[] = "|`{$vo['var']}`|`{$vo['type']}`|{$vo['desc']}|";
                        }
                        $md[] = '';
                    }
                    if ($methods['return']) {
                        $md[] = '* 返回示例';
                        $md[] = '';
                        $md[] = '```json';
                        $return = [];
                        foreach ($methods['return'] as $vo) {
                            $json = \json_decode($vo['desc'], true);
                            if ($json) {
                                $return[$vo['var']] = \json_decode($vo['desc']);
                            } else {
                                $return[$vo['var']] = $vo['desc'];
                            }
                        }
                        $md[] = json_encode($return, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
                        $md[] = '```';
                        $md[] = '';
                    }

                    if ($methods['field']) {
                        $md[] = '* 参数说明';
                        $md[] = '';
                        $md[] = '|字段|类型|说明|';
                        $md[] = '|---|---|---|';
                        foreach ($methods['field'] as $vo) {
                            $md[] = "| `{$vo['var']}`|`{$vo['type']}`| {$vo['desc']} |";
                        }
                    }

                    if (!file_put_contents($this->docsDir . $key . '/' . $class['label'] . '_' . $methods['label'] . '.md', implode("\n", $md))) {
                        $this->tip('文档[' . $methods['name'] . ']写入失败!', true);
                    }
                    $this->tip('文档[' . $methods['name'] . ']写入完成!');
                }
            }
        }
    }

    protected function makeDirectory($data) {
        $directory = [
            [
                'name' => '基础说明',
                'sub' => [
                    [
                        'name' => '必要参数',
                        'url' => 'README',
                    ],
                ],
            ],
        ];
        $this->tip('生成基础目录');
        foreach ($data as $key => $vo) {
            $curData = [
                'name' => $vo['name'],
                'sub' => [],
            ];
            $this->tip('生成[' . $vo['name'] . ']目录');
            foreach ($vo['class'] as $class) {
                foreach ($class['methods'] as $v) {
                    $this->tip('生成[' . $vo['name'] . ']子目录');
                    $curData['sub'][] = [
                        'name' => $v['name'],
                        'url' => $key . '/' . $class['label'] . '_' . $v['label'],
                    ];
                }

            }
            $directory[] = $curData;
        }

        $md = [];
        foreach ($directory as $vo) {
            $md[] = '* ' . $vo['name'];
            foreach ($vo['sub'] as $v) {
                $md[] = "  * [{$v['name']}]({$v['url']})";
            }
        }

        $this->tip('写入目录文件...');
        if (!file_put_contents($this->docsDir . '_sidebar.md', implode("\n", $md))) {
            $this->tip('写入目录失败！', true);
        }
        $this->tip('写入目录成功...');
    }

    protected function getApi() {
        $files = glob(ROOT_PATH . 'app/*/api/*.php');
        $filesData = [];
        foreach ($files as $vo) {
            $filesData[] = '\\' . str_replace('/', '\\', str_replace(ROOT_PATH, '', $vo));
        }
        $data = [];
        foreach ($filesData as $vo) {
            $vo = str_replace('.php', '', $vo);
            $ref = new \ReflectionClass($vo);
            $doc = $this->parser($ref->getDocComment());
            $target = explode('\\', $vo);
            $config = load_config($target[1] . '/' . $target[2] . '/config/config', false);
            $doc['label'] = strtolower(str_replace('Api', '', $target[4]));

            $parents = $ref->getParentClass();
            $parentsMethods = [];
            if ($parents) {
                $parentsMethods = $this->params($parents->getMethods());
            }
            $methods = $this->params($ref->getMethods(\ReflectionMethod::IS_PUBLIC));

            $diff = array_diff($methods, $parentsMethods);

            if (empty($diff)) {
                continue;
            }
            $methodsDoc = [];
            foreach ($diff as $v) {
                if ($diff[$v] == '__construct') {
                    continue;
                }
                $methodDoc = $this->parser($ref->getMethod($v)->getDocComment());
                if (empty($methodDoc) || empty($methodDoc['method'])) {
                    continue;
                }
                $methodDoc['url'] = $target[2] . '/' . $doc['label'] . '/' . $v;
                $methodDoc['label'] = $v;
                $methodsDoc[] = $methodDoc;
            }

            if (empty($methodsDoc)) {
                continue;
            }
            $doc['methods'] = $methodsDoc;
            $data[$target[2]]['name'] = $config['app.name'];
            $data[$target[2]]['class'][] = $doc;
        }

        return $data;
    }

    protected function params($methods) {
        $data = [];
        foreach ($methods as $vo) {
            $data[] = $vo->name;
        }
        return $data;
    }

    protected function parser($doc) {
        $doc = preg_replace('/[ ]+/', ' ', $doc);
        preg_match('#^/\*\*(.*)\*/#s', $doc, $comment);
        $comment = trim($comment[1]);
        preg_match_all('#^\s*\*(.*)#m', $comment, $lines);

        $lines = array_values(array_filter($lines[1]));
        $param = [];
        foreach ($lines as $key => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            if (strpos($line, '@') === 0) {
                $paramInfo = explode(' ', $line, 4);
                $method = str_replace('@', '', $paramInfo[0]);
                $key = str_replace('$', '', $paramInfo[2]);
                if (strpos($paramInfo[0], '@param') !== false || strpos($paramInfo[0], '@return') !== false || strpos($paramInfo[0], '@header') !== false || strpos($paramInfo[0], '@field') !== false) {
                    $param[$method][$key] = [
                        'type' => $paramInfo[1],
                        'var' => $key,
                        'desc' => $paramInfo[3],
                    ];
                } else {
                    $param[$method] = $paramInfo[1];
                }
            } else {
                if (empty($param['name'])) {
                    $param['name'] = $line;
                }
                if ($param['name'] && !$param['desc']) {
                    $param['desc'] = $line;
                }
            }
        }
        return $param;

    }

}