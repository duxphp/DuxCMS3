<?php

/**
 * 访问统计
 * @author  Mr.L <349865361@qq.com>
 */

namespace app\statis\admin;


class SiteViewsAdmin extends \app\system\admin\SystemExtendAdmin {

    protected $_model = 'StatisViews';

    protected function _infoModule() {
        return [
            'info' => [
                'name' => '访问统计',
                'description' => '统计商城访问信息',
            ],
            'fun' => [
                'index' => true,
            ],
        ];
    }

    public function index() {

        $startTime = request('', 'start_time', 0);
        $stopTime = request('', 'stop_time', 0);


        if (empty($startTime)) {
            $startTime = date('Y-m-d', strtotime('-30 day'));

        }
        if (empty($stopTime)) {
            $stopTime = date('Y-m-d', time());
        }
        $pageMaps = [
            'start_time' => $startTime,
            'stop_time' => $stopTime,
        ];

        if ($startTime) {
            $startTime = date('Ymd', strtotime($startTime));
        }
        if ($stopTime) {
            $stopTime = date('Ymd', strtotime($stopTime));
        }

        $siteViews = target('statis/StatisViews')->where([
            'species' => 'site',
            '_sql' => 'date >= ' . $startTime . ' AND date <= ' . $stopTime,
        ])->sum('num');
        $sitePeople = target('statis/StatisViews')->query("select COUNT(DISTINCT user_id) as `count`  from `{pre}statis_views` where species = 'site' and user_id > 0 and date >= " . $startTime . " and date <= " . $stopTime);
        $sitePeople = $sitePeople[0]['count'];
        $this->assign('siteViews', $siteViews);
        $this->assign('sitePeople', $sitePeople);

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

        $data = target('statis/StatisViews')->query("select date,COUNT(DISTINCT user_id) as `count`  from `{pre}statis_views` where species = 'site' and user_id > 0 and date >= " . $startTime . " and date <= " . $stopTime . " group by date");

        $listPeopleData = [];
        foreach ($data as $vo) {
            $listPeopleData[date('Y-m-d', strtotime($vo['date']))] += $vo['count'];
        }

        $peopleData = [];
        foreach ($statsLabel as $vo) {
            if ($listPeopleData[$vo]) {
                $peopleData[] = $listPeopleData[$vo];
            } else {
                $peopleData[] = 0;
            }
        }

        $viewBarJs = target('tools/Echarts', 'service')->bar('site-bar', $statsLabel, [
            [
                'name' => '会员访客',
                'data' => $peopleData,
            ],
            [
                'name' => '站点浏览',
                'data' => $viewData,
            ],
        ], 300);
        $this->assign('siteViewsJs', $viewBarJs);


        $dateParams = [
            [
                'start_time' => date('Y-m-d', time()),
                'stop_time' => date('Y-m-d', time()),
            ],
            [
                'start_time' => date('Y-m-d', strtotime('-7 day')),
                'stop_time' => date('Y-m-d', time()),
            ],
            [
                'start_time' => date('Y-m-d', strtotime('-15 day')),
                'stop_time' => date('Y-m-d', time()),
            ],
            [
                'start_time' => date('Y-m-d', strtotime('-30 day')),
                'stop_time' => date('Y-m-d', time()),
            ],
        ];

        $this->assign([
            'dateParams' => $dateParams,
            'pageMaps' => $pageMaps,
        ]);
        $this->systemDisplay();
    }

}