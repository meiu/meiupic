<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

//获取用户设置

if(isPost()){
    $cnames = getPost('cname');
    $show = getPost('show');

    $data = array();
    foreach ($cnames as $key => $value) {
        if(trim($value) == ''){
            continue;
        }
        $data[$key]['cname'] = trim($value);
        $data[$key]['show'] = isset($show[$key])?1:0;
    }
    if(app('base')->setSetting('user_fields',$data)){
        alert('设置成功！',true);
    }else{
        alert('设置失败！');
    }
}

$fields = app('base')->getSetting('user_fields',true);
$view->assign('fields',$fields);

$view->display('fields.php');