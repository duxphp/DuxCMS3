<?php

/**
 * 微信菜单
 */
namespace app\wechat\model;

use app\system\model\SystemModel;

class WechatMenuModel extends SystemModel {

    protected $infoModel = [
        'pri' => 'menu_id',
        'into' => '',
        'out' => '',
    ];

    /**
     * 获取分类树
     * @param array $where
     * @param int $limit
     * @param string $order
     * @param int $patrntId
     * @return array
     */
    public function loadTreeList(array $where = [], $limit = 0, $order = '', $patrntId = 0) {
        $class = new \dux\lib\Category(['menu_id', 'parent_id', 'name', 'cname']);
        $list = $this->loadList($where, $limit, $order);
        if(empty($list)){
            return [];
        }
        $type = $this->type();
        foreach ($list as $key => $vo) {
            $list[$key]['type_name'] = $type[$vo['type']]['name'];
        }
        return $class->getTree($list, $patrntId);
    }

    public function getWhereInfo($where) {
        $info = parent::getWhereInfo($where);
        if (empty($info)) {
            return [];
        }
        $info['data'] = json_decode($info['data'], true);
        return $info;
    }


    public function _saveBefore($data ,$type) {

        $wechatData = [];
        if($data['type'] == 1) {
            //媒体
            $wechatData['type'] = $data['media_type'];
            $wechatData['media_id'] = $data['media_id'];

        }
        if($data['type'] == 2) {
            //链接
            $wechatData['type'] = 'view';
            $wechatData['url'] = $data['url'];
        }

        if($data['type'] == 3) {
            //小程序
            $wechatData['type'] = 'miniprogram';
            $wechatData['appid'] = $data['appid'];
            $wechatData['pagepath'] = $data['pagepath'];
            $wechatData['url'] = $data['app_url'];
        }

        $data['data'] = json_encode($wechatData);

        if($data['type'] == 1) {
            if(empty($wechatData['type']) || empty($wechatData['media_id'])) {
                $this->error = '回复消息不能为空！';
                return false;
            }
        }
        if($data['type'] == 2) {
            if(strpos($wechatData['url'],'http://', 0) === false && strpos($wechatData['url'],'https://', 0) === false) {
                $this->error = '跳转页面地址必须以http://或http://开头!';
                return false;
            }
        }
        if($data['type'] == 3) {
            if(empty($wechatData['appid']) || empty($wechatData['pagepath'])) {
                $this->error = '小程序信息不完整！';
                return false;
            }
        }

        if($type == 'edit') {
            if ($data['parent_id'] == $data['class_id']) {
                $this->error = '您不能将当前分类设置为上级分类!';
                return false;
            }

            $cat = $this->loadTreeList([], 0, '', $data['class_id']);
            if($cat) {
                foreach ($cat as $vo) {
                    if ($_POST['parent_id'] == $vo['class_id']) {
                        $this->error = '不可以将上一级分类移动到子分类';
                        return false;
                    }
                }
            }

            $catData = $this->countList([
                'parent_id' => $data['parent_id'],
                '_sql' => 'menu_id <> ' . $data['menu_id']
            ]);

        }else {
            $catData = $this->countList([
                'parent_id' => $data['parent_id']
            ]);
        }

        $maxNum = 5;
        if(!$data['parent_id']) {
            $maxNum = 3;
        }

        if($catData >= $maxNum) {
            $this->error = '当前父菜单下的菜单数量不能超过' . $maxNum . '个';
            return false;
        }

        if($data['parent_id'] > 0) {
            $this->where(['menu_id' => $data['parent_id']])->data(['type' => 0, 'data' => ''])->update();
        }

        return $data;
    }


    public function type() {
        return [
            0 => [
                'name' => '无',
            ],
            1 => [
                'name' => '发送消息',
            ],
            2 => [
                'name' => '页面跳转',
            ],
            3 => [
                'name' => '关联小程序',
            ],
        ];
    }


}