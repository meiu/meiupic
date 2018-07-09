<?php
defined('IN_MWEB') or die('access denied');

class SpaceIndex extends Adminbase{

    protected $_submenu = array(
        '个人中心默认应用' => array('index')
    );

    
    function indexAct(){
        if(isPost()){
            $default_app = getPost('default_app');
            app('base')->setSetting('space_default_app',$default_app);

            alert('设置默认应用成功！',true,U('space','index'));
        }

        $default_app = app('base')->getSetting('space_default_app');

        if(!$default_app){
            $default_app = 'album';
        }
        //遍历app目录
        $appsdirs = dirlist('apps',ROOT_DIR);
        $applist = array();

        foreach ($appsdirs as $key => $value) {
            if($value['filename']=='..'){ continue; }
            $infopath = ROOT_DIR.$value['path'].DS.'info.php';
            if(!file_exists($infopath)){ continue;}

            $info = include($infopath);
            $info['id'] = $value['filename'];
            if($info['ismy']){
                $applist[] = $info;
            }
        }

        $this->view->assign('default_app',$default_app);
        $this->view->assign('applist',$applist);
        
        $this->view->display('apps_default.php');
    }
}