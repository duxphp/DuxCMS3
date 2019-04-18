<?php
namespace app\tools\service;
/**
 * 模块接口
 */
class ToolsService extends \app\base\service\BaseService {

    /**
     * 通知发送
     */
    public function notice($type, $label, $receive, $params = [], $path = '', $userStatus = true) {
        $dataInfo = target('tools/ToolsSendData')->getWhereInfo([
            'type' => $type,
            'label' => $label,
        ]);

        if (empty($dataInfo) || !$dataInfo['status']) {
            $this->error = '通知类型未开启！';
            dux_log('['.$type.']['.$label.']通知类型未开启！');
            return false;
        }

        if (empty($dataInfo['class']) || empty($dataInfo['title'])) {
            $this->error = '通知内容不完整！';
            dux_log('['.$type.']['.$label.']通知内容不完整！');
            return false;
        }

        $dataInfo['data'] = json_decode($dataInfo['data'], true);
        $dataInfo['class'] = explode(',', $dataInfo['class']);

        foreach ($dataInfo['class'] as $vo) {
            $curData = $dataInfo['data'][$vo];
            $content = '';
            $data = [];
            if (!is_array($curData)) {
                foreach ($params as $key => $v) {
                    $curData = str_replace('[' . $key . ']', $v, $curData);
                }
                $content = $curData;
            } else {
                $tmpData = $curData['data'];
                foreach ($tmpData as $v) {
                    if (empty($v['key'])) {
                        continue;
                    }
                    if ($params[$v['key']]) {
                        $data[$v['val']] = $params[$v['key']];
                    } else {
                        $data[$v['val']] = $v['key'];
                    }

                }
            }

            $data['tpl'] = $curData['id'];
            $data['url'] = '';

            $siteConfig = target('site/SiteConfig')->getConfig();
            if ($dataInfo['url'] == 'mobile') {
                $data['url'] = $siteConfig['site_wap'] . '/' . $path;
            }
            $status = target('tools/Tools', 'service')->sendMessage([
                'receive' => $receive,
                'class' => $vo,
                'title' => $dataInfo['title'],
                'user_status' => $userStatus,
                'content' => $content,
                'param' => $data,
            ]);
            if (!$status) {
                $this->error = target('tools/Tools', 'service')->getError();
                dux_log('['.$type.']['.$label.']' . $this->error);
                return false;
            }
        }
        return true;

    }

    /**
     * 消息发送
     */
    public function sendMessage($data) {
        $data = [
            'receive' => $data['receive'],
            'class' => html_in($data['class']),
            'title' => html_clear($data['title']),
            'content' => html_in($data['content']),
            'param' => json_encode($data['param']),
            'user_status' => $data['user_status'],
        ];
        if (empty($data['class']) || empty($data['title']) || empty($data['content']) || empty($data['receive'])) {
            $this->error('队列参数不正确!');
        }

        $typeInfo = target('tools/ToolsSendConfig')->defaultType($data['class']);
        //检查接口格式
        if (empty($typeInfo)) {
            return $this->error('未发现相关接口!');
        }

        if (!target($typeInfo['target'], 'send')->check($data)) {
            return $this->error(target($typeInfo['target'], 'send')->getError());
        }
        if (!empty($typeInfo['tpl'])) {
            $siteConfig = target('site/SiteConfig')->getConfig();
            $replace = [
                '[网站名称]' => $siteConfig['info_name'],
                '[网址]' => DOMAIN,
                '[版权信息]' => $siteConfig['info_copyright'],
                '[站点邮箱]' => $siteConfig['info_email'],
                '[站点电话]' => $siteConfig['info_tel'],
                '[内容区域]' => $data['content'],
            ];
            $content = $typeInfo['tpl'];
            foreach ($replace as $key => $vo) {
                $content = str_replace($key, $vo, $content);
            }
            $data['content'] = $content;
        }
        $saveData = array();
        $saveData['type'] = $typeInfo['type'];
        $saveData['title'] = $data['title'];
        $saveData['content'] = $data['content'];
        $saveData['param'] = $data['param'];
        $saveData['receive'] = $data['receive'];
        $saveData['user_status'] = $data['user_status'];
        $saveData['start_time'] = time();
        $id = target('tools/ToolsSend')->add($saveData);
        if (!$id) {
            return $this->error('发送失败!');
        }

        //添加到队列
        if (!target('tools/Queue', 'service')->add('send', $id, '消息发送', 'tools/toolsSend', 'send', 'model', $saveData, 0)) {
            return $this->error('发送失败!');
        }

        return $this->success();
    }

    /**
     * 获取配置
     * @param string $model
     * @param string $pre
     * @return array
     */
    public function getConfig($model = '', $pre = '') {
        $list = target('base/Base')->table($model . '_config')->select();
        $data = array();
        foreach ($list as $vo) {
            if (!empty($pre)) {
                if (strpos($vo['name'], $pre . '_', 0) !== false) {
                    $data[substr($vo['name'], strlen($pre . '_'))] = $vo['content'];
                }
            } else {
                $data[$vo['name']] = $vo['content'];
            }
        }
        return $data;
    }

}
