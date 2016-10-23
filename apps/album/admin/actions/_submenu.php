<?php
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '相册列表' => 'index',
    '分类管理' => 'cate',
    '图片管理' => 'photo',
    '相册设置' => 'setting',
);
$view->assign('submenu',$submenu);
/*二级菜单结束*/