<?php

/**
 * Api管理
 */
namespace app\system\model;

class ConfigApiModel {

    public function loadList() {
        $list = \dux\Config::get('dux.api');
        return $list;
    }


    public function getAllApi() {
        $apiFiles = glob(ROOT_PATH . 'app/*/api/*.php');
        $classNames = [];
        foreach ($apiFiles as $vo) {
            $classNames[] = str_replace('/', '\\', str_replace('.php', '', str_replace(ROOT_PATH, '', $vo)));
        }
        $apiMethonds = [];
        foreach ($classNames as $vo) {
            $paths = explode('\\', $vo);
            $appName = $paths[1];
            $ref = new \ReflectionClass($vo);
            $className = $ref->getName();
            $className = explode('\\', $className);
            $className = end($className);
            $className = substr($className, 0, -3);
            $methonds = [];
            foreach ($ref->getMethods() as $method) {
                if($method->isProtected()) {
                    continue;
                }
                if($method->class <> $vo) {
                    continue;
                }
                if(strstr($method->name, '__')) {
                    continue;
                }
                $methonds[] = $method->name;
            }
            $apiMethonds[$appName][$className] = $methonds;
        }
        return $apiMethonds;
    }

}