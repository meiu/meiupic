<?php 
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '评论列表' => 'index',
    '评论设置' => 'setting',
);
$view->assign('submenu',$submenu);
