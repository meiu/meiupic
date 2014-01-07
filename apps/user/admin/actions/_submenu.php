<?php
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '用户' => 'index',
    '扩展字段' => 'fields',
    '用户设置' => 'setting',
);
$view->assign('submenu',$submenu);
/*二级菜单结束*/