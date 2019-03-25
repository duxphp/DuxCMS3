<?php

/**
 * 占位图片
 */

namespace app\tools\middle;

class PlaceholderMiddle extends \app\base\middle\BaseMiddle {

    protected function index() {

        $_GET['no_log'] = true;
        $width = intval($this->params['width']);
        $width = $width ? $width : '128';
        $height = intval($this->params['height']);
        $height = $height ? $height : '128';
        $text = html_clear($this->params['text']);
        $text = $text ? $text : '缩略图';

        $html = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'" preserveAspectRatio="none"><rect width="'.$width.'" height="'.$height.'" fill="#eee"/><text text-anchor="middle" x="'.round($width/2, 0).'" y="'.round($height/2, 0).'" style="fill:#aaa;font-weight:bold;font-size:1.6rem;font-family:Arial,Helvetica,sans-serif;dominant-baseline:central">'.$text.'</text></svg>';
        return $this->run([
            'html' => $html
        ]);
    }



}