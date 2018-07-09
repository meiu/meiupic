<?php

Class SpaceClass{
    //获取用户中心的菜单列表
    public function getAppMenus(){
        $cache = cache::instance();
        $applist = $cache->get('app_menus');

        if(!$applist){
            //遍历app目录
            $appsdirs = dirlist('apps',ROOT_DIR);
            $applist = array();

            foreach ($appsdirs as $key => $value) {
                if($value['filename']=='..'){ continue; }

                $infopath = ROOT_DIR.$value['path'].DS.'info.php';
                if(!file_exists($infopath)){ continue;}

                $info = include($infopath);

                $info['id'] = $value['filename'];
                $applist[] = $info;
            }

            $cache->set('app_menus',$applist,array('life_time'=>3600));
        }

        return $applist;
    }
}