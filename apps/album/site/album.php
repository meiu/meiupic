<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$page = getGet('page',1);
$m_album = M('albums');

$where = 'deleted=0';
$where .= ' and uid ='.intval($_G['user']['id']);

$totalCount = $m_album->count($where);
$pageurl = U('album','album',array('page'=>'%page%'));

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_album->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'id desc'
));
$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/album-list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '用户中心 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->display('album/album.php');
}