<?php
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '图片管理' => 'index',
    '相册列表' => 'album',
    '分类管理' => 'cate',
    '标签管理' => 'tags',
    '相册设置' => 'setting',
    '回收站' => 'recyclebin',
);
$view->assign('submenu',$submenu);
/*二级菜单结束*/