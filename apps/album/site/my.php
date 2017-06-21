<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

//获取左侧菜单
$site_title = '用户中心 - '.getSetting('site_title');
$view->assign('site_title',$site_title);
$view->display('album/my.php');