<?php
defined('IN_MWEB') or die('access denied');

class BaseApps extends Adminbase{

    protected $_submenu = array(
        '应用列表' => array('index'),
        '设置默认应用'  => array('default')
    );

    function indexAct(){
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

        $this->view->assign('applist',$applist);
        $this->view->display('apps.php');
    }

    function iconAct(){
        $id = safestr(getGet('id'));
        $iconpath = ROOT_DIR.'apps'.DS.$id.DS.'icon.png';

        header('Content-type: image/png');
        echo readfile($iconpath);
        exit;
    }

    function addmenuAct(){
        $appid = getPost('appid');
        $name  = getPost('name');

        //获取当前的菜单配置
        $menuslist = app('base')->getSetting('admin_menu',true);
        
        $exist = false;
        foreach($menuslist as $key=>$value){
            if($appid==$value['app']){
                $exist = true;
            }
        }

        $infopath = ROOT_DIR.'apps'.DS.$appid.DS.'info.php';

        $appinfo = include($infopath);
        if(!$exist){
            if(isset($appinfo['adminmenu'])){
                foreach ($appinfo['adminmenu'] as $key => $value) {
                    $value['enable'] = true;
                    $menuslist[] = $value;
                }
            }else{
                $menuslist[] = array('name'=>$name,'app'=>$appid,'fixed'=>false,'enable'=>true);
            }
        }

        app('base')->setSetting('admin_menu',$menuslist);

        exit('1');
    }

    function defaultAct(){
        if(isPost()){
            $default_app = getPost('default_app');
            app('base')->setSetting('default_app',$default_app);

            alert('设置默认应用成功！',true,U('base','apps','a=default'));
        }

        $default_app = app('base')->getSetting('default_app');

        if(!$default_app){
            $default_app = 'base';
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
            $applist[] = $info;
        }

        $this->view->assign('default_app',$default_app);
        $this->view->assign('applist',$applist);
        
        $this->view->display('apps_default.php');
    }
}