<?php

/**
 * 调试信息
 */

namespace app\system\middle;

class DebugMiddle extends \app\base\middle\BaseMiddle {

    protected function data() {
        $platform = $this->params['platform'];
        $page = html_clear($this->params['page']);
        $content = html_clear($this->params['content']);
        $hash = sha1($content);
        if(empty($content)) {
            return $this->run();
        }

        $info = target('system/SystemDebug')->getWhereInfo([
            'hash' => $hash,
            'platform' => $platform,
            'page' => $page,
        ]);

        if ($info) {
            target('system/SystemDebug')->where(['debug_id' => $info['debug_id']])->data([
                'num[+]' => 1,
                'update_time' => time(),
            ])->update();
        } else {
            target('system/SystemDebug')->add([
                'platform' => $platform,
                'page' => $page,
                'content' => $content,
                'hash' => $hash,
                'num' => 1,
                'create_time' => time(),
                'update_time' => time()
            ]);
        }

        return $this->run();
    }


}