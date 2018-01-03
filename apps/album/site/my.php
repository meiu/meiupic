<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$aid = intval(getGet('aid'));
$page = getGet('page',1);
$m_photo = M('album_photos');

$where = 'deleted=0';
$where .= ' and uid ='.intval($_G['user']['id']);
$urlparam = array('page'=>'%page%');
if($aid){
    $where .= ' and album_id = '.$aid;
    //获取相册信息
    $albumInfo = M('albums')->load($aid);
    $view->assign('albumInfo',$albumInfo);
    $urlparam['aid'] = $aid;
}
$totalCount = $m_photo->count($where);
$pageurl = U('album','my',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_photo->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'id desc'
));
$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/photo-list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '用户中心 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->display('album/my.php');
}