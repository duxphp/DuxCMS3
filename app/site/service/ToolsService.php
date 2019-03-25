<?php
namespace app\site\service;

/**
 * 工具
 */
class ToolsService extends \app\base\service\BaseService {

    public function coverImage($image, $width = 300, $height = 300) {
        $imageInfo = pathinfo($image);
        $imageUrls = parse_url($image);
        $dir = str_replace($imageInfo['basename'], '', $imageUrls['path']);
        $oImageFile = ROOT_PATH . ltrim($imageUrls['path'], '/');
        $nImageFile = ROOT_PATH . ltrim($dir, '/') . 'cover_' . $imageInfo['basename'];
        $nimageUrl = $imageInfo['dirname'] . '/' . 'cover_' . $imageInfo['basename'];
        if (is_file($oImageFile)) {
            $imageObj = new \dux\lib\Image($oImageFile);
            $imageObj->thumb($width, $height);
            if (!$imageObj->output($nImageFile, $imageInfo['extension'])) {
                return $this->error($imageObj->getError());
            }
            $data = $nimageUrl;
        } else {
            $data = $image;
        }
        return $this->success($data);
    }

}
