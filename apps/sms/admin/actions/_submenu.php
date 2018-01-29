<?php 
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '短信发送日志' => 'index',
    '短信服务设置' => 'setting',
);
$view->assign('submenu',$submenu);
