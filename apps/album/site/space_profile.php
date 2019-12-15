<?php



$uid = intval(getGet('id'));
$page = getGet('page',1);

$uinfo = M('users')->load($uid);
$view->assign('uinfo',$uinfo);
$iinfo = M('users_info')->load($uid,'*','uid');
if(!$iinfo) $iinfo = M('users_info')->loadDefault();
$view->assign('iinfo',$iinfo);

$fields = app('base')->getSetting('user_fields',true);
$view->assign('fields',$fields);
$view->assign('uid',$uid);

$view->assign('site_title','用户资料 - '.getSetting('site_title'));
$view->display('album/space_profile.php');