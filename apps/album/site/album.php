<?php 
defined('IN_MWEB') or die('access denied');

$uid = intval(getGet('id'));
$page = getGet('page',1);
$m_album = M('albums');

$uinfo = M('users')->load($uid);
$view->assign('uinfo',$uinfo);

$where = 'deleted=0';
$where .= ' and uid ='.$uid;
if($G_['user']['id'] != $uid){//访客，那么不取出私人照片
    $where .= ' and priv_type=0';
}
$totalCount = $m_album->count($where);
$pageurl = U('album','album',array('page'=>'%page%','id'=>$uid));

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
$view->assign('uid',$uid);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/space_album_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '相册列表 - 用户中心 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/space_album.php');
}