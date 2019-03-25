<?php

namespace app\tools\model;

use Intervention\Image\ImageManager;

/**
 * 图片工具
 */
class ToolsImageModel {


    /**
     * 通用合并
     * @param $imagePath 主图
     * @param $image2Path 合并图
     * @param $path 保存路径
     * @param array $config 配置
     * @param array $managerParam
     * @return mixed
     */
    public function imageSynthesis($imagePath,$image2Path,$path,$config = [],$managerParam = ['driver' => 'imagick']){

        $imageConfig = [
            'width'         => 400,
            'height'        => 500
        ];

        if(!empty($config['image']))
            $imageConfig = array_merge($imageConfig, $config['image']);

        $image2Config = [
            'width'         => 120,
            'height'        => 120
        ];

        if(!empty($config['image2']))
            $image2Config = array_merge($image2Config, $config['image2']);

        $locationConfig = [
            'position'          => 'top-left',
            'x'                 => $imageConfig['width'] - $image2Config['width'] - 10,
            'y'                 => $imageConfig['height'] - $image2Config['height'] - 10,
        ];

        if(!empty($config['location']))
            $locationConfig = array_merge($locationConfig, $config['location']);

        $manager = new ImageManager($managerParam);

        $baseImage = $manager->make(get_image_make($imagePath))->resize($imageConfig['width'], $imageConfig['height']);
        $image = $manager->make(get_image_make($image2Path))->resize($image2Config['width'], $image2Config['height']);
        $baseImage->insert($image, $locationConfig['position'], $locationConfig['x'], $locationConfig['y']);

        $savePath = ROOT_PATH . $path;
        $baseImage->save($savePath, 100);

        return $path;
    }


    /**
     * 获取底图
     * @param $imagePath 二维码地址
     * @param $data 文本参数
     * @param $path 报错底图地址
     * @param array $config 配置
     * @param array $managerParam
     * @return string
     */
    public function getBaseImage($imagePath,$data,$path,$config = [],$managerParam = ['driver' => 'imagick']){

        $imgConfig = [
            'width'         => 400,
            'height'        => 580,
            'color'         => '#fff'
        ];

        if(!empty($config['img']))
            $imgConfig = array_merge($imgConfig, $config['img']);

        $imageConfig = [
            'width'         => 90,
            'height'        => 90,
            'location'     => [
                'position'          => 'top-left',
                'x'                 => $imgConfig['width'] - 100,
                'y'                 => $imgConfig['height'] - 100,
            ]
        ];

        if(!empty($config['image']))
            $imageConfig = array_merge($imageConfig, $config['image']);

        $dataConfig = [
            'title'             => [
                'size'          => '17px',
                'color'         => '#222',
                'align'         => 'start',
                'valign'        => 'bottom',
                'location'      => [
                    'x'             => 20,
                    'y'             => $imgConfig['height'] - 80
                ],
                'fun'           => function($str){
                    return cut_string($str,24);
                }
            ],
            'name'              => [
                'size'          => '14px',
                'color'         => '#777',
                'align'         => 'start',
                'valign'        => 'bottom',
                'location'      => [
                    'x'             => 20,
                    'y'             => $imgConfig['height'] - 50
                ],
                'fun'           => function($str){
                    return cut_string($str,24);
                }
            ],
            'price'             => [
                'size'          => '15px',
                'color'         => '#f0506e',
                'align'         => 'start',
                'valign'        => 'bottom',
                'location'      => [
                    'x'             => 20,
                    'y'             => $imgConfig['height'] - 20
                ]
            ]
        ];

        if(!empty($config['data']))
            $dataConfig = array_merge($dataConfig, $config['data']);


        $fontFile = ROOT_PATH . 'public/fonts/pingfang.ttf';

        $manager = new ImageManager($managerParam);

        //创建空白画布
        $baseImage = $manager->canvas($imgConfig['width'], $imgConfig['height'], $imgConfig['color']);

        //图片合成
        $image = $manager->make(get_image_make($imagePath))->resize($imageConfig['width'], $imageConfig['height']);
        $baseImage->insert($image, $imageConfig['location']['position'], $imageConfig['location']['x'], $imageConfig['location']['y']);


        //文字处理
        foreach ($data as $k=>$v){

            if(!isset($dataConfig[$k]))
                continue;

            $textConfig = $dataConfig[$k];

            $text = $v;

            if(isset($textConfig['fun']) && is_callable($textConfig['fun']))
                $text = $textConfig['fun']($text);

            //文字合成
            $baseImage->text($text, $textConfig['location']['x'], $textConfig['location']['y'], function ($font) use ($textConfig, $fontFile) {
                $font->file($fontFile);
                if(isset($textConfig['size']))
                    $font->size($textConfig['size']);
                if(isset($textConfig['color']))
                    $font->color($textConfig['color']);
                if(isset($textConfig['align']))
                    $font->align($textConfig['align']);
                if(isset($textConfig['valign']))
                    $font->valign($textConfig['valign']);
            });

        }

        $savePath = ROOT_PATH . $path;
        $baseImage->save($savePath, 100);

        $baseImage->destroy();

        return $savePath;
    }


    /**
     * 获取合成后的推广图片
     * @param $baseImagePath 底图地址
     * @param $imagePath 宣传图地址
     * @param $path 报错地址
     * @param array $config 配置
     * @param array $managerParam
     * @return mixed
     */
    public function getPromoteImage($baseImagePath,$imagePath,$path,$config = [],$managerParam = ['driver' => 'imagick']){

        $imageConfig = [
            'width'         => 400,
            'height'        => 460,
            'location'     => [
                'position'          => 'top-left',
                'x'                 => 0,
                'y'                 => 0
            ]
        ];

        if(!empty($config['image']))
            $imageConfig = array_merge($imageConfig, $config['image']);

        $manager = new ImageManager($managerParam);

        $baseImage = $manager->make(get_image_make($baseImagePath));
        //图片合成
        $image = $manager->make(get_image_make($imagePath))->resize($imageConfig['width'], $imageConfig['height']);
        $baseImage->insert($image, $imageConfig['location']['position'], $imageConfig['location']['x'], $imageConfig['location']['y']);

        $savePath = ROOT_PATH . $path;
        $baseImage->save($savePath, 100);

        $baseImage->destroy();

        return $path;
    }


}