<?php

namespace app\tools\service;

use Hisune\EchartsPHP\ECharts;

/**
 * Echarts图标生成
 */
class EchartsService extends \app\base\service\BaseService {

    /**
     * 饼图
     * @param $id
     * @param $name
     * @param $data
     * @param $height
     * @param bool $numShow
     * @return string
     */
    public function pie($id, $name, $data, $height, $numShow = true) {

        $chart = new ECharts();

        $label = [];

        foreach ($data as $vo) {
            $label[] = $vo['name'];
        }

        $option = [
            'tooltip' => [
                'trigger' => 'item',
                'formatter' => $numShow ? "{a} <br/>{b} : {c} ({d}%)" : "{a} <br/>{b} {d}%" ,
            ],
            'legend' => [
                'orient' => 'vertical',
                'left' => 'right',
                'data' => $label,
            ],
            'series' => [
                'name' => $name,
                'type' => 'pie',
                'radius' => '55%',
                'center' => ['40%', '50%'],
                'data' => $data,
                'itemStyle' => [
                    'emphasis' => [
                        'shadowBlur' => 10,
                        'shadowOffsetX' => 0,
                        'shadowColor' => 'rgba(0, 0, 0, 0.5)',
                    ],
                ],
            ],
        ];
        $chart->setOption($option);
        return $chart->render($id, ['style' => 'height: ' . $height . 'px;']);
    }

    /**
     * 柱状图
     * @param $id
     * @param $labels
     * @param $data
     * @param $height
     * @return string
     */
    public function bar($id, $labels, $data, $height) {
        $chart = new ECharts();
        $label = [];
        $series = [];
        $legend = [];

        foreach ($data as $vo) {
            $legend[] = $vo['name'];
            $label[] = $vo['name'];
            $series[] = [
                'name' => $vo['name'],
                'type' => 'bar',
                'data' => $vo['data'],
            ];
        }

        $option = [
            'tooltip' => [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'shadow',
                ],
            ],
            'legend' => [
                'data' => $legend,
            ],
            'grid' => [
                'left' => '3%',
                'right' => '4%',
                'bottom' => '3%',
                'containLabel' => true,
            ],
            'xAxis' => [
                'type' => 'category',
                'data' => $labels
            ],
            'yAxis' => [
                'type' => 'value',
            ],
            'series' => $series
        ];
        $chart->setOption($option);
        return $chart->render($id, ['style' => 'height: ' . $height . 'px;']);
    }

    /**
     * 堆叠图
     * @param $id
     * @param $name
     * @param $labels
     * @param $data
     * @param $height
     * @return string
     */
    public function pile($id, $name, $labels, $data, $height) {
        $chart = new ECharts();
        $label = [];
        $series = [];
        $legend = [];

        foreach ($data as $vo) {
            $legend[] = $vo['name'];
            $label[] = $vo['name'];
            $series[] = [
                'name' => $vo['name'],
                'type' => 'bar',
                'stack' => $vo['stack'] ? $vo['stack'] : $name,
                'data' => $vo['data'],
            ];
        }

        $option = [
            'tooltip' => [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'shadow',
                ],
            ],
            'legend' => [
                'data' => $legend,
            ],
            'grid' => [
                'left' => '3%',
                'right' => '4%',
                'bottom' => '3%',
                'containLabel' => true,
            ],
            'xAxis' => [
                'type' => 'category',
                'data' => $labels
            ],
            'yAxis' => [
                'type' => 'value',
            ],
            'series' => $series
        ];
        $chart->setOption($option);
        return $chart->render($id, ['style' => 'height: ' . $height . 'px;']);
    }

    /**
     * 嵌套图
     * @param $id
     * @param $name
     * @param $data
     * @param $height
     * @param bool $numShow
     * @return string
     */
    public function nested($id, $name, $data, $height, $numShow = true) {

        $chart = new ECharts();

        $label = [];

        $typeData = [];
        foreach ($data as $vo) {
            $label[] = $vo['name'];
            $typeData[$vo['type']][] = $vo;
        }

        $option = [
            'tooltip' => [
                'trigger' => 'item',
                'formatter' => $numShow ? "{a} <br/>{b} : {c} ({d}%)" : "{a} <br/>{b} {d}%" ,
            ],
            'legend' => [
                'orient' => 'vertical',
                'left' => 'right',
                'data' => $label,
            ],
            'series' => [
                [
                    'name' => $name,
                    'type' => 'pie',
                    'selectedMode' => 'single',
                    'radius' => [0, '30%'],
                    'center' => ['40%', '50%'],
                    'data' => $typeData[0],
                    'label' => [
                        'normal' => [
                            'position' => 'inner',
                        ]
                    ],
                    'labelLine' => [
                        'normal' => [
                            'show' => false,
                        ]
                    ],
                    'itemStyle' => [
                        'emphasis' => [
                            'shadowBlur' => 10,
                            'shadowOffsetX' => 0,
                            'shadowColor' => 'rgba(0, 0, 0, 0.5)',
                        ],
                    ],
                ],
                [
                    'name' => $name,
                    'type' => 'pie',
                    'radius' => ['45%', '60%'],
                    'center' => ['40%', '50%'],
                    'data' => $typeData[1],
                    'itemStyle' => [
                        'emphasis' => [
                            'shadowBlur' => 10,
                            'shadowOffsetX' => 0,
                            'shadowColor' => 'rgba(0, 0, 0, 0.5)',
                        ],
                    ],
                ]
            ],
        ];
        $chart->setOption($option);
        return $chart->render($id, ['style' => 'height: ' . $height . 'px;']);
    }

    /**
     * 漏斗
     * @param $id
     * @param $name
     * @param $data
     * @param $height
     * @return string
     */
    public function funnel($id, $name, $data, $height) {
        $chart = new ECharts();
        $label = [];
        foreach ($data as $vo) {
            $label[] = $vo['name'];
        }

        $option = [
            'tooltip' => [
                'trigger' => 'item',
                'formatter' => "{a} <br/>{b} : {c} ({d}%)",
            ],
            'legend' => [
                'data' => $label,
            ],
            'calculable' => true,
            'series' => [
                'name' => $name,
                'type' => 'funnel',
                'left' => '5%',
                'top' => 50,
                'bottom' => 0,
                'width' => '90%',
                'min' => 0,
                'max' => 100,
                'minSize' => '0%',
                'maxSize' => '100%',
                'sort' => 'descending',
                'gap' => 2,
                'label' => [
                    'normal' => [
                        'show' => true,
                        'position' => 'inside'
                    ],
                    'emphasis' => [
                        'textStyle' => [
                            'fontSize' => 20
                        ]
                    ]
                ],
                'labelLine' => [
                    'normal' => [
                        'length' => 10,
                        'lineStyle' => [
                            'width' => 1,
                            'type' => 'solid'
                        ]
                    ]
                ],
                'itemStyle' => [
                    'normal' => [
                        'borderColor' => '#fff',
                        'borderWidth' => 1
                    ]
                ],
                'data' => $data
            ],
        ];
        $chart->setOption($option);
        return $chart->render($id, ['style' => 'height: ' . $height . 'px;']);
    }

}

