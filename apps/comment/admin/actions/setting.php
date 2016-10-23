<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

if(isPost()){
    $setting = getPost('setting');
    if(app('base')->setSetting('comment_setting',$setting)){
        alert('设置成功！',true);
    }else{
        alert('设置失败！');
    }
}

$setting = app('base')->getSetting('comment_setting',true);
$view->assign('setting',$setting);

$view->display('setting.php');