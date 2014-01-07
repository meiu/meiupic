<?php
defined('IN_MWEB') or die('access denied');

$act = getGet('a','index');
$view->assign('act',$act);

switch ($act) {
    case 'index':
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

        $view->assign('applist',$applist);
        $view->display('apps.php');
        break;
    case 'icon':
        $id = getGet('id');
        $iconpath = ROOT_DIR.'apps'.DS.$id.DS.'icon.png';

        header('Content-type: image/png');
        echo readfile($iconpath);
        exit;
        break;
    case 'addmenu':
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
        if(!$exist){
            if($appid=='base'){
                $menuslist[] = array('name'=>'首页','app'=>'base','mod'=>'index','fixed'=>true,'enable'=>true);
                $menuslist[] = array('name'=>'模版','app'=>'base','mod'=>'template','fixed'=>true,'enable'=>true);
                $menuslist[] = array('name'=>'设置','app'=>'base','mod'=>'setting','fixed'=>true,'enable'=>true);
            }elseif($appid=='cms'){
                $menuslist[] = array('name'=>'内容','app'=>'cms','mod'=>'content','fixed'=>false,'enable'=>true);
                $menuslist[] = array('name'=>'模型','app'=>'cms','mod'=>'model','fixed'=>false,'enable'=>true);
            }else{
                $menuslist[] = array('name'=>$name,'app'=>$appid,'fixed'=>false,'enable'=>true);
            }
        }

        app('base')->setSetting('admin_menu',$menuslist);

        exit('1');
        break;
}