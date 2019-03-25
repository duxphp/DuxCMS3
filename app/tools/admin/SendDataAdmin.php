<?php

/**
 * 推送设置
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\tools\admin;


class SendDataAdmin extends \app\system\admin\SystemAdmin {

    protected $_model = 'ToolsSendData';

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return array(
            'info' => array(
                'name' => '参数设置',
                'description' => '推送功能相关参数设置',
            ),
        );
    }

    /**
     * 站点设置
     */
    public function index() {
        $data = target($this->_model)->dataList();
        $dataType = [];
        foreach ($data as $key => $vo) {
            if (empty($vo['type'])) {
                continue;
            }
            $dataType[] = [
                'name' => $vo['name'],
                'value' => $key
            ];
        }
        $type = request('', 'type', $dataType[0]['value']);
        $classList = target('tools/ToolsSendConfig')->classList();

        $dataList = $data[$type]['type'];
        if (empty($dataList)) {
            $this->error('类型不存在！');
        }
        $tmpData = target($this->_model)->loadList([
            'type' => $type,
        ]);
        foreach ($tmpData as $key => $vo) {
            $tmpData[$key]['class'] = explode(',', $vo['class']);
            $tmpData[$key]['data'] = json_decode($vo['data'], true);
        }
        $data = [];
        foreach ($tmpData as $vo) {
            $data[$vo['label']] = $vo;
        }
        if (!isPost()) {
            $appList = target('wechat/WechatMiniapp')->loadList();
            $this->assign('dataType', $dataType);
            $this->assign('dataList', $dataList);
            $this->assign('classList', $classList);
            $this->assign('data', $data);
            $this->assign('type', $type);
            $this->assign('appList', $appList);
            $this->systemDisplay();
        } else {
            target($this->_model)->beginTransaction();
            foreach ($dataList as $label => $vo) {
                $curData = [];
                $curData['type'] = $type;
                $curData['label'] = $label;
                $curData['title'] = $_POST[$label . '_title'];
                $curData['url'] = $_POST[$label . '_url'];
                $curData['status'] = $_POST[$label . '_status'];
                $curData['class'] = implode(',', (array)$_POST[$label . '_class']);

                $postData = $_POST[$label. '_data'];

                $subData = [];
                foreach ($postData as $k => $d) {
                    if(is_array($d)) {
                        $array = [];
                        foreach ($d['data']['key'] as $i => $v) {
                            if(empty($v) && empty($d['data']['val'][$i])){
                                continue;
                            }
                            $array[$i] = [
                                'key' => $v,
                                'val' => $d['data']['val'][$i],
                            ];
                        }
                        $subData[$k]['id'] = $d['id'];
                        $subData[$k]['data'] = $array;
                    }else {
                        $subData[$k] = $d;
                    }
                }


                $curData['data'] = json_encode($subData);

                $curInfo = target($this->_model)->getWhereInfo([
                    'type' => $type,
                    'label' => $label
                ]);
                if($curInfo) {
                    $curData['data_id'] = $curInfo['data_id'];
                    $status = target($this->_model)->edit($curData);
                }else {
                    $status = target($this->_model)->add($curData);
                }
                if(empty($status)) {
                    $this->error(target($this->_model)->getError());
                }
            }
            target($this->_model)->commit();
            $this->success('配置成功！');
        }
    }

}