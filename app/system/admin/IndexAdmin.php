<?php

/**
 * 系统首页
 */

namespace app\system\admin;

class IndexAdmin extends \app\system\admin\SystemAdmin {

    /**
     * 模块信息
     */
    protected function _infoModule() {
        return [
            'info' => [
                'name' => '系统首页',
                'description' => '系统基本信息参数',
            ],
        ];
    }

    /**
     * 首页
     */
    public function index() {

        $siteViews = target('statis/StatisViews')->where([
            'species' => 'site',
        ])->sum('num');
        $this->assign('siteViews', $siteViews);

        $systemStatis = target('system/SystemStatistics')->countStats();
        $this->assign('systemStatis', $systemStatis);

        $startTime = date('Y-m-d 0:0:0', strtotime("-30 day"));
        $stopTime = date('Y-m-d  H:i:s');

        if ($startTime) {
            $startTime = date('Ymd', strtotime($startTime));
        }
        if ($stopTime) {
            $stopTime = date('Ymd', strtotime($stopTime));
        }

        $statsLabel = [];
        for ($i = strtotime($startTime); $i <= strtotime($stopTime); $i += 86400) {
            $statsLabel[] = date('Y-m-d', $i);
        }

        $data = target('statis/StatisViews')->query("select date,sum(num) as view_sum  from `{pre}statis_views` where species = 'site' and date >= " . $startTime . " and date <= " . $stopTime . " group by date");
        $listViewData = [];
        foreach ($data as $vo) {
            $listViewData[date('Y-m-d', strtotime($vo['date']))] += $vo['view_sum'];
        }

        $viewData = [];
        foreach ($statsLabel as $vo) {
            if ($listViewData[$vo]) {
                $viewData[] = $listViewData[$vo];
            } else {
                $viewData[] = 0;
            }
        }

        $viewBarJs = target('tools/Echarts', 'service')->bar('order-bar', $statsLabel, [
            [
                'name' => '站点访问量',
                'data' => $viewData,
            ],
        ], 400);

        $this->assign('viewBarJs', $viewBarJs);
        $this->systemDisplay();
    }

}